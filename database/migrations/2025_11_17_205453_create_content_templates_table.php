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
        Schema::create('content_templates', function (Blueprint $table) {
            $table->id();
            $table->string('entity', 50);             // community | product | album
            $table->string('name', 190);
            $table->json('hero')->nullable();         // {title, subtitle, image}
            $table->longText('about_html')->nullable();
            $table->json('blocks')->nullable();
            $table->timestamps();

            $table->unique(['entity', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_templates');
    }
};
