<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models;

class ClearDeletedPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除已軟刪除文章';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $posts = Models\Post::onlyTrashed()->get();
        // var_dump($posts); // test
        // foreach ($posts as $post) {
        //     $post->forceDelete();
        // } // for each
        
        // \Log::info('移除所有被刪除文章');
        return;
    } // handle
}
