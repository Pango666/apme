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
        Schema::create('newsletter_subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('email')->unique();
            $t->string('name')->nullable();
            $t->string('status', 30)->default('pending'); // pending|subscribed|unsubscribed|bounced
            $t->string('token', 64)->unique();           // confirm/unsubscribe token
            $t->timestamp('confirmed_at')->nullable();
            $t->timestamps();
            $t->index(['status', 'confirmed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};
