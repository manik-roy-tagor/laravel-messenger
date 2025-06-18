<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = [
        'user_id',          // The sender's ID
        'receiver_id',      // The receiver's ID
        'type',             // e.g., 'text', 'video', 'audio'
        'content',          // The message content for text types
        'file_path',        // Path to any uploaded file
    ];
   public function user()
    {
        return $this->belongsTo(User::class, 'user_id');  // সেন্ডার
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');  // রিসিভার
    }
}
