<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('pharmacy_name');
            $table->text('address')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('official_email')->nullable();
            $table->text('receipt_footer')->nullable();
            $table->boolean('show_pharmacist_name')->default(true);
            $table->decimal('vat_percent', 5, 2)->default(11);
            $table->unsignedBigInteger('default_packaging_fee')->default(2500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};