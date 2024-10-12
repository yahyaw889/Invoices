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
        Schema::create('attachment_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Invoices::class , 'invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class , 'user_id');
            $table->string('attachment');
            $table->string('name' , 240);
            $table->string('type' , 20);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachment_invoices');
    }
};
