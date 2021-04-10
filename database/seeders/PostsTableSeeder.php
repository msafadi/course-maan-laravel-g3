<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Post::create([
            'title' => 'Sample Post Title',
            'slug' => 'sample-post-title',
            'content' => 'Sample post content. Sample post content.',
            'category_id' => 1,
        ]);
    }
}
