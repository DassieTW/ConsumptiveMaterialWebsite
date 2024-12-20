<?php

namespace App\Policies;

use App\Models\ConsumptiveMaterial;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasicInfoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Login  $user
     * @return mixed
     */
    public function viewBasicInfo(Login $user)
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    } // viewBasicInfo

    // 權限3以上才能新增料件
    public function addNewMats(Login $user)
    {
        if (intval($user->priority) < 3) {
            return true;
        } else {
            return false;
        } // if else 
    }
}
