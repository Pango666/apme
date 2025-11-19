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
        Schema::create('campaign_recipients', function (Blueprint $t) {
            $t->id();
            $t->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $t->foreignId('subscriber_id')->constrained('newsletter_subscribers')->cascadeOnDelete();
            $t->string('email');
            $t->string('status', 20)->default('queued'); // queued|sent|failed
            $t->text('error')->nullable();
            $t->timestamp('sent_at')->nullable();
            $t->timestamps();
      
            $t->unique(['campaign_id','subscriber_id']);
            $t->index(['campaign_id','status']);
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_recipients');
    }
};
