<?php

namespace App\Policies;

use App\Models\Inbound;
use App\Models\Login;
use Illuminate\Auth\Access\HandlesAuthorization;

class InboundPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Login  $user
     * @return boolean
     */
    public function viewInbound(Login $user)
    {
        if (intval($user->priority) < 3) {
            return true;
        } else {
            return false;
        } // if else
    } // viewInbound
}
