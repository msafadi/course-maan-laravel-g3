<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'category_id', 'content', 'status', 'image',
    ];

    // Attribute Accessors
    // image_url
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
            //return Storage::disk('public')->url($this->image);
        }

        return 'https://via.placeholder.com/150/?Text=No+Image';
    }
}
