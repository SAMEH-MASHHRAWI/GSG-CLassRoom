<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();
            $table->foreignId('subscription_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();
            $table->integer('amount');
            $table->char('currancy_code',3);
            $table->string('payment_gateway');
            $table->enum('status',['pending','completed','faild']);
            $table->string('gateway_refrance_id')->nullable();
            $table->unsignedSmallInteger('price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
