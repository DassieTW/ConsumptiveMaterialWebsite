<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subject = Models\Subject::where('name', '測試主題')->first();
        for ($index = 0; $index < 10; $index++) {
            $post = new Models\Post;
            $post->content = 'Laravel 8.x demo';
            $post->subject_id = $subject->id;
            $post->user_id = 1 ;
            $post->save();
        } // for

        $subject = Models\Subject::where('name', '測試主題2')->first();
        for ($index = 0; $index < 15; $index++) {
            $post = new Models\Post;
            $post->content = 'Hi, I am Vincent !';
            $post->subject_id = $subject->id;
            $post->user_id = 2 ;
            $post->save();
        }
    }
}
