<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_obat', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('item_id')->constrained()->restrictOnDelete();
            $table->string('batch_number')->unique();
            $table->unsignedInteger('quantity')->default(0);
            $table->date('received_at');
            $table->date('expiration_date')->nullable();
            $table->timestamps();

            $table->index(['item_id', 'received_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_obat');
    }
};
