<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->integer('failed_attempts')
            ->default(0);

            $table->boolean('is_suspended')
            ->default(false);

            $table->timestamp('password_changed_at')
            ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'failed_attempts',
                'is_suspended',
                'password_changed_at'
            ]);

        });
    }
};