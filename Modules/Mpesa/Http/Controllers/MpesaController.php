<?php

namespace Modules\Mpesa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanTransaction;
use Modules\Mpesa\Entities\MpesaTransaction;
use Modules\Mpesa\Mpesa;

class MpesaController extends Controller
{


    public function capture_payment(Request $request)
    {
        $data = $request->validate(['loan_id' => ['required','integer','exists:loans,id'], 'amount' => ['required','numeric','min:1'], 'mobile' => ['required','regex:/^(?:254|0)?[17]\\d{8}$/']]);
        $loan = Loan::findOrFail($data['loan_id']);
        if ((float) $data['amount'] > (float) $loan->total_outstanding_derived) {
            throw ValidationException::withMessages(['amount' => 'The amount cannot exceed the outstanding loan balance.']);
        }
        $gateway = new Mpesa($loan->id, 'loan');
        $url = $gateway->getSetting('mpesa.test_mode') === 'yes' ? $gateway->getSetting('mpesa.sandbox_url') : $gateway->getSetting('mpesa.live_url');
        $key = $gateway->getSetting('mpesa.consumer_key');
        $secret = $gateway->getSetting('mpesa.consumer_secret');
        $shortcode = $gateway->getSetting('mpesa.business_shortcode');
        $timestamp = Carbon::now()->format('YmdHis');
        $phone = preg_replace('/^0/', '254', $data['mobile']);
        $token = Cache::remember('mpesa.oauth.'.sha1($url.$key), now()->addMinutes(55), function () use ($url, $key, $secret) {
            return Http::withBasicAuth($key, $secret)->acceptJson()->timeout(15)->retry(2, 250)->get(rtrim($url, '/').'/oauth/v1/generate', ['grant_type' => 'client_credentials'])->throw()->json('access_token');
        });
        $response = Http::withToken($token)->acceptJson()->timeout(20)
            ->post(rtrim($url, '/').'/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $shortcode,
                'Password' => base64_encode($shortcode.$gateway->getSetting('mpesa.passkey').$timestamp),
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => round((float) $data['amount'], 2),
                'PartyA' => $phone,
                'PartyB' => $shortcode,
                'PhoneNumber' => $phone,
                'CallBackURL' => $gateway->getResultUrl(),
                'AccountReference' => (string) $loan->id,
                'TransactionDesc' => 'Loan Repayment'
            ])->throw();
        $result = $response->json();
        MpesaTransaction::create(['loan_id' => $loan->id, 'client_id' => $loan->client_id, 'merchant_request_id' => $result['MerchantRequestID'] ?? null, 'checkout_request_id' => $result['CheckoutRequestID'] ?? null, 'amount' => $data['amount'], 'phone_number' => $phone, 'status' => (($result['ResponseCode'] ?? null) === '0') ? 'pending' : 'failed', 'result_code' => $result['ResponseCode'] ?? null, 'result_description' => $result['ResponseDescription'] ?? null]);
        return response()->json(['status' => 'pending', 'message' => 'Check your phone for the M-Pesa prompt. Payment remains pending until confirmed.', 'checkout_request_id' => $result['CheckoutRequestID'] ?? null], 202);
    }

    public function webhook(Request $request)
    {
        $payload = $request->json()->all();
        $callback = data_get($payload, 'Body.stkCallback');
        if (!is_array($callback) || empty($callback['CheckoutRequestID'])) return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Unsupported callback'], 422);
        $transaction = MpesaTransaction::where('checkout_request_id', $callback['CheckoutRequestID'])->first();
        if (!$transaction) return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Unknown transaction'], 404);
        if ($transaction->processed_at) return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        $metadata = collect(data_get($callback, 'CallbackMetadata.Item', []))->pluck('Value', 'Name');
        $code = (string) ($callback['ResultCode'] ?? '1');
        if ($code !== '0') { $transaction->update(['status' => 'failed', 'result_code' => $code, 'result_description' => $callback['ResultDesc'] ?? null, 'callback_payload' => $payload, 'processed_at' => now()]); return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']); }
        $receipt = $metadata->get('MpesaReceiptNumber');
        $amount = (float) $metadata->get('Amount', 0);
        if (!$receipt || abs($amount - (float) $transaction->amount) > .01 || !$transaction->loan_id) { $transaction->update(['status' => 'review', 'result_code' => $code, 'result_description' => 'Callback did not match the initiated payment.', 'callback_payload' => $payload]); return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Payment requires review'], 422); }
        DB::transaction(function () use ($transaction, $receipt, $amount, $payload, $code) {
            $locked = MpesaTransaction::whereKey($transaction->id)->lockForUpdate()->first();
            if ($locked->processed_at || LoanTransaction::where('reference', $receipt)->exists()) return;
            (new Mpesa($locked->loan_id, 'loan'))->processPayment(['module' => 'loan', 'loan_id' => $locked->loan_id, 'reference' => $receipt, 'amount' => $amount]);
            $locked->update(['transaction_id' => $receipt, 'status' => 'completed', 'result_code' => $code, 'result_description' => 'Payment confirmed', 'callback_payload' => $payload, 'processed_at' => now()]);
        });
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
