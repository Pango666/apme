<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['name', 'subject', 'preheader', 'html', 'scheduled_at', 'status', 'sent_count', 'error_count'];
    protected $casts = ['scheduled_at' => 'datetime'];

    public function recipients()
    {
        return $this->hasMany(CampaignRecipient::class);
    }
}
