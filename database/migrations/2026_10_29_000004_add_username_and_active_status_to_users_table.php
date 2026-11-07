<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->boolean('is_active')->default(true)->after('customer_id');
        });

        DB::table('users')->orderBy('id')->get(['id', 'email'])->each(function (object $user): void {
            $baseUsername = Str::before((string) $user->email, '@');
            $baseUsername = $baseUsername !== '' ? $baseUsername : 'user';
            $username = $baseUsername;

            if (DB::table('users')->where('username', $username)->exists()) {
                $username = $baseUsername.'-'.$user->id;
            }

            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'is_active']);
        });
    }
};