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
        Schema::create('albums', function (Blueprint $t) {
            $t->id();
            $t->string('title', 190);
            $t->string('slug', 190)->unique();
            $t->string('type', 50)->nullable()->default('feria');
            $t->date('date')->nullable();
            $t->string('place', 190)->nullable();
            $t->text('summary')->nullable();

            //landing
            $t->string('hero_title', 190)->nullable();
            $t->string('hero_subtitle', 190)->nullable();
            $t->string('hero_image', 190)->nullable();
            $t->string('hero_button_text', 190)->nullable();
            $t->string('hero_button_url', 190)->nullable();
            $t->string('hero_button_color', 190)->nullable();
            $t->string('hero_button_text_color', 190)->nullable();
            $t->string('hero_button_background_color', 190)->nullable();
            $t->longText('about_html')->nullable();
            $t->json('blocks')->nullable();

            $t->timestamps();

            //indexes
            $t->index('type');
            $t->index('date');
        });

        Schema::create('album_photos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('album_id')->constrained('albums')->cascadeOnDelete()->cascadeOnUpdate();
            $t->string('path', 255);     // /storage/albums/xx.webp
            $t->string('caption', 190)->nullable();
            $t->unsignedSmallInteger('order')->default(1);

            $t->index(['album_id', 'order']);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums_tables');
    }
};
