<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table): void {
            $table->string('unit')->default('Pcs')->after('category');
            $table->unsignedInteger('minimum_stock')->default(0)->after('stock');
            $table->boolean('is_controlled')->default(false)->after('minimum_stock');
            $table->boolean('is_active')->default(true)->after('is_controlled');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table): void {
            $table->dropColumn(['unit', 'minimum_stock', 'is_controlled', 'is_active']);
        });
    }
};
