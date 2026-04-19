<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('document_uploads', function (Blueprint $table) {
            $table->boolean('is_reuploaded')->default(false)->after('verification_comment');
            $table->timestamp('reuploaded_at')->nullable()->after('is_reuploaded');
        });
    }

    public function down()
    {
        Schema::table('document_uploads', function (Blueprint $table) {
            $table->dropColumn(['is_reuploaded', 'reuploaded_at']);
        });
    }
};