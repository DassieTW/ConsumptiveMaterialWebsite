<?php

namespace App\Policies;

use App\Models\Checking_inventory;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckInventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function viewCheckInvent(Login $user)  // 權限1234都能看到盤點
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }
}
