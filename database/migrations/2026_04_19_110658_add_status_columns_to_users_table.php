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
            if (!Schema::hasColumn('users', 'application_status')) {
                $table->string('application_status')->default('pending')->after('degree_program');
            }
            if (!Schema::hasColumn('users', 'document_status')) {
                $table->string('document_status')->default('pending')->after('application_status');
            }
            if (!Schema::hasColumn('users', 'interview_status')) {
                $table->string('interview_status')->default('pending')->after('document_status');
            }
            if (!Schema::hasColumn('users', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('interview_status');
            }
            if (!Schema::hasColumn('users', 'final_status')) {
                $table->string('final_status')->default('pending')->after('payment_status');
            }
            if (!Schema::hasColumn('users', 'decision_notes')) {
                $table->text('decision_notes')->nullable()->after('final_status');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'application_status',
                'document_status',
                'interview_status',
                'payment_status',
                'final_status',
                'decision_notes'
            ]);
        });
    }
};