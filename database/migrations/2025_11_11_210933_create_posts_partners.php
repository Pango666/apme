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
        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->string('title', 190);
            $t->string('slug', 190)->unique();
            $t->text('excerpt')->nullable();
            $t->longText('body')->nullable();
            $t->string('cover_path', 255)->nullable();    // 1200x675 webp
            $t->timestamp('published_at')->nullable();
            $t->boolean('is_published')->default(true);
            $t->timestamps();

            //indexes
            $t->index('slug');
            $t->index('published_at');
            $t->index('is_published');
        });
        
        Schema::create('partners', function (Blueprint $t) {
            $t->id();
            $t->string('name', 190);
            $t->string('logo_path', 255)->nullable();     // 600x600 png/webp
            $t->string('url', 255)->nullable();
            $t->timestamps();

            //indexes
            $t->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_partners');
    }
};
