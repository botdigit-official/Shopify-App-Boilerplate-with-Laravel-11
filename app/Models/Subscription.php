<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'store_id',
        'plan',
        'trial_ends_at'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function isOnTrial()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }
}