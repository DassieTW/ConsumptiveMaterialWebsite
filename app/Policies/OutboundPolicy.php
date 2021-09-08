<?php

namespace App\Policies;

use App\Models\Outbound;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutboundPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function viewOutbound(Login $user)  // 權限1234都能看到出庫
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function outboundReturn(Login $user)  // 權限1234都能看到O庫 退料
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function outboundReturnRecord(Login $user)  // 權限1234都能看到O庫 退料紀錄表
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function outboundReturnSerialNum(Login $user)  // 權限1234都能看到O庫 退料單
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function outboundPickup(Login $user)  // 權限1234都能看到O庫 領料
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function outboundPickupRecord(Login $user)  // 權限123 能看到O庫 領料紀錄表
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function outboundPickupSerialNum(Login $user)  // 權限123 能看到O庫 領料單
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    }
}
