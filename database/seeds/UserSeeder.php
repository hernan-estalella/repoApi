<?php

use Illuminate\Database\Seeder;
use App\Post;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 10)->create()->each(function ($user) {
            $user->posts()->saveMany(factory(App\Post::class, random_int(1,5))->make())->each(function ($post) {
                $post->ratings()->saveMany(factory(App\Rating::class, random_int(1,5))->make());
            });
        });
    }
}
