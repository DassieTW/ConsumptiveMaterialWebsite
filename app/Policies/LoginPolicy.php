<?php

namespace App\Policies;

use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LoginPolicy         // 所有用戶管理相關權限
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Login  $user
     * @return mixed
     */
    public function create(Login $user)   // 新增用戶權限
    {
        if (intval($user->priority) < 2) {
            return true;
        } else {
            return false;
        } // if else
    } // create

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function updatePriority(Login $user)  // 修改權限
    {
        // 權限1才能修改權限
        if (intval($user->priority) < 2) {
            return true;
        } else {
            return false;
        } // if else
    } // updatePriority

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Login  $user
     * @param  \App\Models\xxx  $post
     * @return mixed
     */
    // public function delete(Login $user, xxx $post) // 需要用到另一個Model的例子 限制只有同個人能刪
    // {
    //     return $user->id === $post->user_id;
    // } // delete

    /**
     * Determine whether the user can login.
     * Prevent user trying to go to login page from url after logged in.
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function canLogin(?Login $user)
    {
        if (\Auth::user() != null) {
            return Response::deny('You are already logged in.');
        } else {
            return Response::allow();
        } // if else

    } // canLogin

    /**
     * Determine whether the user can search and update the model.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function searchAndUpdateUser(Login $user)
    {
        // 權限1才能用戶訊息 查詢/刪除
        if (intval($user->priority) < 2) {
            return true;
        } else {
            return false;
        } // if else
    } // searchAndUpdate

    public function searchAndUpdatePeople(Login $user)
    {
        // 權限123才能用戶訊息 查詢/刪除
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else
    } // searchAndUpdate

    public function newPeopleInfo(Login $user)
    {
        // 權限123才能新增人員訊息
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else
    } // searchAndUpdate

    public function canSwitchSites(Login $user)
    {
        // available_dblist清單非空才能切換廠別
        if ( !is_null($user->available_dblist) && mb_strlen($user->available_dblist) > 0) {
            return true;
        } else {
            return false;
        } // if else
    } // canSwitchSites

    public function canAddSitesToUser(Login $user)
    {
        // 只有權限0 也就是我們IT 才能添加新DB到user的 "available_dblist"
        if ( intval($user->priority) < 1) {
            return true;
        } else {
            return false;
        } // if else
    } // canAddSitesToUser
}
