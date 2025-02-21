<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'shop_domain',
        'access_token',
        'nonce',
        'installed'
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}