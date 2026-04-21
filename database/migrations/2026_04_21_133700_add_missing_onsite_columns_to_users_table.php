<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if columns exist before adding
            if (!Schema::hasColumn('users', 'onsite_requested_at')) {
                $table->timestamp('onsite_requested_at')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'onsite_verified_at')) {
                $table->timestamp('onsite_verified_at')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'onsite_verified_by')) {
                $table->foreignId('onsite_verified_by')->nullable()->constrained('users');
            }
            
            // Make sure these columns exist
            if (!Schema::hasColumn('users', 'onsite_verification_pending')) {
                $table->boolean('onsite_verification_pending')->default(false);
            }
            
            if (!Schema::hasColumn('users', 'onsite_verified')) {
                $table->boolean('onsite_verified')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'onsite_requested_at',
                'onsite_verified_at',
                'onsite_verified_by'
            ]);
        });
    }
};