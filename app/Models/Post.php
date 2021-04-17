<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'category_id', 'content', 'status', 'image',
    ];

    protected $perPage = 15;

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

    // Model Events:
    // creating, created, updating, updated, saving, saved, deleting, deleted
    // restoring, restored, retrived

    protected static function booted()
    {
        /*static::addGlobalScope('published', function(Builder $builder) {
            $builder->where('status', '=', 'published');
        });*/

        static::observe(new PostObserver());

        static::addGlobalScope(new PublishedScope());

        /*static::saving(function(Post $post) {
            $post->slug = Str::slug($post->title);
        });*/

        /*static::forceDeleted(function(Post $post) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
        });*/
    }

    // Local scope "published"
    public function scopePublished(Builder $builder)
    {
        $builder->where('status', '=', 'published');
    }

    // Local scope "draft"
    public function scopeDraft(Builder $builder)
    {
        $builder->where('status', '=', 'draft');
    }

    public function scopeStatus(Builder $builder, $status = 'published')
    {
        $builder->where('status', '=', $status);
    }
}
