<?php

namespace App\Policies;

use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LoginPolicy
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
     * @param  \App\Models\Login  $post
     * @return mixed
     */
    public function view(Login $user, Login $post)
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
        if( intval($user->priority) < 2 ) {
            return true;
        }else {
            return false;
        } // if else 
    } // create

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function updatePriority(Login $user)
    {
        return $user->priority < 2;  // 權限1才能修改權限
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Login  $user
     * @param  \App\Models\Login  $post
     * @return mixed
     */
    public function delete(Login $user, Login $post)
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can login. 
     * Prevent trying to go to login from url after already login.
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function canLogin(?Login $user)
    {
        if( \Auth::user() != null ) {
            return Response::deny('You already logged in.');
        } else {
            return Response::allow();
        } // if else
        
    } // canLogin

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Login  $user
     * @param  \App\Models\Login  $post
     * @return mixed
     */
    public function restore(Login $user, Login $post)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Login  $user
     * @param  \App\Models\Login  $post
     * @return mixed
     */
    public function forceDelete(Login $user, Login $post)
    {
        //
    }
}