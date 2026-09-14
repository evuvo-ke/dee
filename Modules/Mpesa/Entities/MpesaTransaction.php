<?php
namespace Modules\Mpesa\Entities;
use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    protected $fillable = ['loan_id','client_id','merchant_request_id','checkout_request_id','transaction_id','amount','phone_number','status','result_code','result_description','callback_payload','processed_at'];
    protected $casts = ['callback_payload' => 'array', 'processed_at' => 'datetime', 'amount' => 'decimal:6'];
}
