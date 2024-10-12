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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number' , 50)->unique();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('note')->nullable();
            $table->decimal('total');
            $table->decimal('amount_collection' , 8  , 2);
            $table->decimal('amount_commission' , 8  , 2);
            $table->decimal('discount' , 8  , 2);
            $table->decimal('value_vat' , 8  , 2);
            $table->string('rate_vat' , 999);
            $table->foreignIdFor(\App\Models\Section::class , 'section_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Product::class , 'product_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Status::class )->constrained()->cascadeOnDelete();
            $table->date('payment_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
