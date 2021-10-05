<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlarmPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function viewAlarm(Login $user)  // 權限123 能看到報警系統
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    } // viewAlarm

}
