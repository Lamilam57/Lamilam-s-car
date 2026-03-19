<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppFeedback extends Model
{
    protected $fillable = [
        'user_id',
        'rating',
        'type',
        'subject',
        'message',
        'status',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}