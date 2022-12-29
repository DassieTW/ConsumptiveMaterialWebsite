<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\Bulletin;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;

class EditNewsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function editNews(Login $user)  // 權限1 能更新佈告欄
    {
        if (intval($user->priority) < 2) {
            return true;
        } else {
            return false;
        } // if else 
    } // editNews

}
