<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table): void {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->string('category');
            $table->decimal('selling_price', 15, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->timestamps();

            $table->index(['category', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
