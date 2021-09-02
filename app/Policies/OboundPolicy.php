<?php

namespace App\Policies;

use App\Models\O庫;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;

class OboundPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function viewObound(Login $user)  // 權限1234都能看到O庫
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundIn(Login $user)  // 權限123能看到O庫 入庫
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundInSearch(Login $user)  // 權限123能看到O庫 入庫查詢
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundStockUpload(Login $user)  // 權限1234能看到O庫 庫存上傳
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundStockSearch(Login $user)  // 權限123能看到O庫 庫存查詢
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundISNSearch(Login $user)  // 權限123能看到O庫 料件查詢
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundReturn(Login $user)  // 權限1234都能看到O庫 退料
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundReturnRecord(Login $user)  // 權限1234都能看到O庫 退料紀錄表
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundReturnSerialNum(Login $user)  // 權限1234都能看到O庫 退料單
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundNewMat(Login $user)  // 權限123 能看到O庫 新增料件
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundPickup(Login $user)  // 權限1234都能看到O庫 領料
    {
        if (intval($user->priority) <= 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundPickupRecord(Login $user)  // 權限123 能看到O庫 領料紀錄表
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    }

    public function oboundPickupSerialNum(Login $user)  // 權限123 能看到O庫 領料單
    {
        if (intval($user->priority) < 4) {
            return true;
        } else {
            return false;
        } // if else 
    }
}
