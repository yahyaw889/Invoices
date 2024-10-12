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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Invoices::class, 'invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Status::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, 'user_id');
            $table->date('payment_date')->nullable();
            $table->integer('total_payment')->default(0);
            $table->integer('remaining_payment')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_note')->nullable();
            $table->integer('total_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
