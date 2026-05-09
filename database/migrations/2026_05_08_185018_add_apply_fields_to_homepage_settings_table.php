<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('homepage_settings', function (Blueprint $table) {
        // Idagdag ang mga nawawalang columns
        $table->text('apply_on_site')->nullable();
        $table->text('apply_online')->nullable();
        $table->string('apply_example_toc')->nullable();
        $table->string('apply_example_folder')->nullable();
    });
}

public function down(): void
{
    Schema::table('homepage_settings', function (Blueprint $table) {
        $table->dropColumn(['apply_on_site', 'apply_online', 'apply_example_toc', 'apply_example_folder']);
    });
}
};
