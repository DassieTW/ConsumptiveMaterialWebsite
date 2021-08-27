<?php

namespace App\Policies;

use App\Models\廠別;
use App\Models\客戶別;
use App\Models\機種;
use App\Models\製程;
use App\Models\線別;
use App\Models\領用部門;
use App\Models\領用原因;
use App\Models\入庫原因;
use App\Models\儲位;
use App\Models\發料部門;
use App\Models\退回原因;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Login  $user
     * @return mixed
     */
    public function viewAny(Login $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Login  $user
     * @param  \App\Models\廠別  $inputData
     * @return mixed
     */
    public function viewFactory(Login $user, 廠別 $inputData)
    {
        //
    }

    public function viewClient(Login $user, 客戶別 $inputData)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Login  $user
     * @return mixed
     */
    public function create(Login $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Login  $user
     * @param  \App\Models\廠別  $post
     * @return mixed
     */
    public function update(Login $user, 廠別 $post)
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Login  $user
     * @param  \App\Models\廠別  $post
     * @return mixed
     */
    public function delete(Login $user, 廠別 $post)
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Login  $user
     * @param  \App\Models\廠別  $post
     * @return mixed
     */
    public function restore(Login $user, 廠別 $post)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Login  $user
     * @param  \App\Models\廠別  $post
     * @return mixed
     */
    public function forceDelete(Login $user, 廠別 $post)
    {
        //
    }
}
