<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('requirements', function (Blueprint $table) {
        $table->string('submission_type')->default('gdrive_link')->after('note');
    });
}

public function down()
{
    Schema::table('requirements', function (Blueprint $table) {
        $table->dropColumn('submission_type');
    });
}
};
