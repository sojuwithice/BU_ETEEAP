<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('document_uploads', function (Blueprint $table) {
            $table->string('verification_reason')->nullable()->after('status');
            $table->text('verification_comment')->nullable()->after('verification_reason');
            $table->timestamp('verified_at')->nullable()->after('verification_comment');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('document_uploads', function (Blueprint $table) {
            $table->dropColumn(['verification_reason', 'verification_comment', 'verified_at', 'verified_by']);
        });
    }
};