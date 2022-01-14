<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();
        //$this->call([UsersTableSeeder::class]);
        //$this->call([CategorySeeder::class]);
        //$this->call([TagSeeder::class]);
        //\App\Models\Topic::factory(300)->create();
        //$this->call([TagTopicSeeder::class]);
        //\App\Models\Message::factory(500)->create();
        //$this->call([TopicLikeSeeder::class]);
        //$this->call([FavouriteSeeder::class]);
        //$this->call([CommentSeeder::class]);
        $this->call([CommentLikeSeeder::class]);



    }
}
