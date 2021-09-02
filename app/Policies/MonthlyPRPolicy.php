<?php

namespace App\Policies;


use App\Models\月請購_單耗;
use App\Models\月請購_站位;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MonthlyPRPolicy         // 所有月請購相關權限
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Login  $user
     * 
     * @return mixed
     */
    public function viewMonthlyPR(Login $user)   // 是否能使用月請購
    {
        if( intval($user->priority) < 4 ) {
            return true;
        }else {
            return false;
        } // if else 
    }
}