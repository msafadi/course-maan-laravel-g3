<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    // Profile belongs to User (Invers of One-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
