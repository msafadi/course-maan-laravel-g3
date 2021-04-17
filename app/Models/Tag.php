<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug'
    ];

    protected static function booted()
    {
        static::creating(function(Tag $tag) {
            $tag->slug = Str::slug($tag->name);
        });
    }

    // Many-to-Many (Post belongs to many tags and tag belongs to many posts)
    public function posts()
    {
        return $this->belongsToMany(
            Post::class,     // Related model
            'post_tag',     // Pivot table
            'tag_id',      // FK for the current model in Pivot table
            'post_id',       // FK for the related model in Pivto table
            'id',           // PK for current model
            'id'            // PK for related model
        );
    }
}
