<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('order_id', 100)->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['pending', 'settlement', 'cancel', 'expire', 'failure'])->default('pending');
            $table->string('payment_type', 50)->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status', 50)->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}