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

    // Inverse of one-to-many (Post belongs to one category)
    public function category()
    {
        return $this->belongsTo(
            Category::class    // Related model 
        //    'category_id',      // FK for the related model in the current model
        //    'id'                // PK for the related model
        )->withDefault([
            'name' => 'No Cat.'
        ]);
    }

    // Many-to-Many (Post belongs to many tags and tag belongs to many posts)
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,     // Related model
            'post_tag',     // Pivot table
            'post_id',      // FK for the current model in Pivot table
            'tag_id',       // FK for the related model in Pivto table
            'id',           // PK for current model
            'id'            // PK for related model
        );
    }
}
