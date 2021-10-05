<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index', ['posts' => Post::cursor()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null(Auth::user())) {
            return redirect(route('login'));
        } // if

        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['error'=>$validator->errors()], 401);            
        } // if no content
        
        $fn_store = function () use ($request) {
            $input = $request->all();

            try {
                // var_dump($request->input()); // test
                $post = new Post;
                $post->content = $request->input('content');
                $post->subject_id = 0;
                $post->user_id = Auth::id();
                $post->save();
                return redirect(route('posts.index'));
            } catch (\Exception $e) {
                return response()->json(['error' => 'Unable to create'], 400);
            }
        };

        $return = \DB::transaction($fn_store);
        return $return;
    } // store

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $user = Auth::user();
        if (is_null($user) || $user->cant('update', $post)) {
            \Log::channel('posts')->info('錯誤用戶嘗試編輯', ['user' => $user]);
            return redirect(route('posts.index'));
        }
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $post->content = $request->input('content');
        $post->save();
        return redirect(route('posts.show', ['post' => $post]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect(route('posts.index'));
    }
}
