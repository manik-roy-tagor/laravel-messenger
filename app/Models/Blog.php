<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title', 'content', 'user_id'];  // যে ফিল্ডগুলো মাস-এসেবল

    public function user()
    {
        return $this->belongsTo(User::class);  // প্রত্যেক ব্লগের অথর
    }
}
