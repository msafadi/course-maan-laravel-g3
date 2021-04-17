<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(1)->create();

        //Category::factory(3)->create();
        
        Tag::factory(10)->create();

        /*$this->call([
            PostsTableSeeder::class
        ]);*/
    }
}
