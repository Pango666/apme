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
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('body')->nullable();
            $t->string('cover_path')->nullable();    // 1200x675 webp
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });
        
        Schema::create('partners', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('logo_path')->nullable();     // 600x600 png/webp
            $t->string('url')->nullable();
            $t->timestamps();
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
