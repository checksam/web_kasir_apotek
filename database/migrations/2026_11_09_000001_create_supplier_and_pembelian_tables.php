<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('contact_person');
            $table->string('phone');
            $table->text('address');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['name', 'contact_person']);
        });

        Schema::create('pembelian', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('purchase_number')->unique();
            $table->date('purchase_date');
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status', 30);
            $table->timestamps();

            $table->index(['supplier_id', 'purchase_date']);
        });

        Schema::create('detail_pembelian', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelian')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->restrictOnDelete();
            $table->string('batch_number')->unique();
            $table->date('expiration_date')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
        Schema::dropIfExists('pembelian');
        Schema::dropIfExists('suppliers');
    }
};
