<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\Bulletin;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EditNewsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function editNews(Login $user)  // 權限1、2 能更新佈告欄
    {
        if (intval($user->priority) < 3) {
            return true;
        } else {
            return false;
        } // if else 
    } // editNews

    public function canPostToOtherSite(Login $user) // 權限1 能發布公告到別的廠區
    {
        if (intval($user->priority) < 2) {
            return Response::allow();
        } else {
            return Response::deny('You cannot post bulletin on other sites.');
        } // else 
        
    } // canPostToOtherSite

}
