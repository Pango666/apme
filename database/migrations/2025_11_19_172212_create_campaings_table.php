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
        Schema::create('campaigns', function (Blueprint $t) {
            $t->id();
            $t->string('name', 180);
            $t->string('subject', 190);
            $t->string('preheader', 190)->nullable();
            $t->longText('html');              
            $t->timestamp('scheduled_at')->nullable();
            $t->string('status', 20)->default('draft'); // draft|scheduled|sending|sent|failed
            $t->unsignedInteger('sent_count')->default(0);
            $t->unsignedInteger('error_count')->default(0);
            $t->timestamps();
            $t->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
