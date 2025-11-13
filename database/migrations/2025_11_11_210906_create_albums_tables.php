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
            $t->string('title');
            $t->string('slug')->unique();
            $t->enum('type', ['feria', 'portafolio', 'galeria'])->default('feria');
            $t->date('date')->nullable();
            $t->string('place')->nullable();
            $t->text('summary')->nullable();
            $t->timestamps();
        });

        Schema::create('album_photos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('album_id')->constrained()->cascadeOnDelete();
            $t->string('path');     // /storage/albums/xx.webp
            $t->string('caption')->nullable();
            $t->unsignedSmallInteger('order')->default(0);
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
