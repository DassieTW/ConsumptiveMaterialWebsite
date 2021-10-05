<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models;

class PostControllerTest extends TestCase
{
    public function testUpdate()
    {
        $post = new Models\Post;
        $post->content = '';
        $post->subject_id = 0;
        $post->user_id = 0;
        $post->save();

        $this->put(route('posts.update', ['post' => $post]), [
            'content' => 'Laravel 6.0 tutorial day 21-2'
        ]);

        $this->assertDatabaseHas('posts', [
            'content' => 'Laravel 6.0 tutorial day 21-2',
        ]);
    } // test update

    public function testEditWithoutUserShouldRedirectToIndex()
    {
        $post = new Models\Post;
        $post->content = '';
        $post->subject_id = 0;
        $post->user_id = 0;
        $post->save();
        $response = $this->get(route('posts.edit', ['post' => $post]));
        $response->assertRedirect(route('posts.index'));
    } // test edit not login

    public function testEditWithCorrectUserShouldShowPostIndex()
    {
        $user = Models\User::factory()->create();
        $post = new Models\Post;
        $post->content = '';
        $post->subject_id = 0;
        $post->user_id = $user->id;
        $post->save();
        $response = $this->actingAs($user)
            ->get(route('posts.edit', ['post' => $post]));
        $response->assertViewIs('posts.edit');
    } // test edit login with the right user

    public function testEditWithIncorrectUserShouldRedirectToIndex()
    {
        $users = Models\User::factory(2)->create();
        $post = new Models\Post;
        $post->content = '';
        $post->subject_id = 0;
        $post->user_id = $users[0]->id;
        $post->save();
        $response = $this->actingAs($users[1])
            ->get(route('posts.edit', ['post' => $post]));
        $response->assertRedirect(route('posts.index'));
    } // test login and other user tried to edit others post

    public function testDestroy()
    {
        $post = new Models\Post;
        $post->content = 'Laravel 6.0 tutorial day 21-3';
        $post->subject_id = 0;
        $post->user_id = 0;
        $post->save();

        $this->delete(route('posts.destroy', ['post' => $post]));

        $this->assertSoftDeleted('posts', [
            'content' => 'Laravel 6.0 tutorial day 21-3',
        ]);
    } // test destory

    public function testIndex()
    {
        $response = $this->get(route('posts.index'));
        $response->assertViewIs('posts.index');
    } // test Index

    public function testCreateWithoutUserShouldRedirectToLogin()
    {
        $response = $this->get(route('posts.create'));
        $response->assertRedirect(route('login'));
    } // testCreateWithoutUserShouldRedirectToLogin

    public function testCreateWithUserShouldShowCreatePage()
    {
        $user = Models\User::factory()->create();
        $response = $this->actingAs($user)->get(route('posts.create'));
        $response->assertViewIs('posts.create');
    } // testCreateWithUserShouldShowCreatePage

    public function testStore()
    {
        $user = Models\User::factory()->create();
        $this->actingAs($user)->post(route('posts.store'), [
            'content' => 'Laravel 6.0 tutorial day 21'
        ]);

        $this->assertDatabaseHas('posts', [
            'content' => 'Laravel 6.0 tutorial day 21',
        ]);
    } // test store function 

} // end class
