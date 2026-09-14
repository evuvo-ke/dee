<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::create('mpesa_transactions', function (Blueprint $table) { $table->bigIncrements('id'); $table->unsignedBigInteger('loan_id')->nullable()->index(); $table->unsignedBigInteger('client_id')->nullable()->index(); $table->string('merchant_request_id')->nullable()->index(); $table->string('checkout_request_id')->nullable()->unique(); $table->string('transaction_id')->nullable()->unique(); $table->decimal('amount', 65, 6); $table->string('phone_number', 24)->nullable(); $table->string('status', 32)->default('pending')->index(); $table->string('result_code')->nullable(); $table->text('result_description')->nullable(); $table->json('callback_payload')->nullable(); $table->timestamp('processed_at')->nullable(); $table->timestamps(); }); }
    public function down(): void { Schema::dropIfExists('mpesa_transactions'); }
};
