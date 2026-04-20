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
            if (!Schema::hasColumn('users', 'interview_setup')) {
                $table->string('interview_setup')->nullable()->after('final_status');
            }
            if (!Schema::hasColumn('users', 'interview_location')) {
                $table->string('interview_location')->nullable()->after('interview_setup');
            }
            if (!Schema::hasColumn('users', 'interview_date')) {
                $table->date('interview_date')->nullable()->after('interview_location');
            }
            if (!Schema::hasColumn('users', 'interview_time')) {
                $table->string('interview_time')->nullable()->after('interview_date');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'interview_setup',
                'interview_location',
                'interview_date',
                'interview_time'
            ]);
        });
    }
};