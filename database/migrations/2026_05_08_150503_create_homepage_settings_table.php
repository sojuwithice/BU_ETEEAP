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
    Schema::create('homepage_settings', function (Blueprint $table) {
        $table->id();

        $table->string('hero_headline')->nullable();
        $table->string('hero_highlight')->nullable();
        $table->string('hero_image')->nullable();

        $table->text('about_main')->nullable();
        $table->text('about_more')->nullable();

        $table->string('dean_name')->nullable();
        $table->string('dean_title')->nullable();
        $table->string('dean_image')->nullable();

        $table->string('news_rss')->nullable();

        $table->string('apply_address')->nullable();
        $table->string('apply_link')->nullable();
        $table->string('apply_qr')->nullable();

        $table->json('programs')->nullable();
        $table->json('faqs')->nullable();

        $table->string('contact_email')->nullable();
        $table->string('contact_fb')->nullable();
        $table->string('contact_map')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_settings');
    }
};
