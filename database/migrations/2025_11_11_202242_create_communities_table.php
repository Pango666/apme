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
        Schema::create('communities', function (Blueprint $t) {
            $t->id();
            $t->string('name', 190);
            $t->string('slug', 190)->unique();
            $t->string('province', 190)->nullable();
            $t->text('description')->nullable();

            //landings
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
            
            //ubicacion GPS
            $t->decimal('latitude',10,7)->nullable();
            $t->decimal('longitude',10,7)->nullable();
            $t->timestamps();

            //indexes
            $t->index('name');
            $t->index('province');
            $t->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};
