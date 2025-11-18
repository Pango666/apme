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
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('community_id')->nullable()->constrained('communities')->nullOnDelete()->cascadeOnUpdate();
            $t->string('name', 190);
            $t->string('slug', 190)->unique();
            $t->string('type', 80)->nullable();
            $t->text('description')->nullable();
            $t->decimal('price_bs', 10, 2)->nullable()->default(0);
            $t->boolean('is_active')->default(true);

            //landing corta
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
            $t->index('name');
            $t->index('slug');
            $t->index('community_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
