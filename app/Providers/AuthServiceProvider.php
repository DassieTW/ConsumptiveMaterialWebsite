<?php

namespace App\Providers;

use App\Models\Login;
use App\Policies\LoginPolicy;

use App\Models\月請購_單耗;
use App\Policies\MonthlyPRPolicy;

use App\Models\ConsumptiveMaterial;
use App\Policies\BasicInfoPolicy;

use App\Models\Inbound;
use App\Policies\InboundPolicy;

use App\Models\O庫;
use App\Policies\OboundPolicy;

use App\Models\Outbound;
use App\Policies\OutboundPolicy;

use App\Models\Inventory;
use App\Policies\AlarmPolicy;

use App\Models\Checking_inventory;
use App\Policies\CheckInventPolicy;

use App\Models\Bulletin;
use App\Policies\EditNewsPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Login::class => LoginPolicy::class,
        月請購_單耗::class => MonthlyPRPolicy::class,
        ConsumptiveMaterial::class => BasicInfoPolicy::class,
        Inbound::class => InboundPolicy::class,
        O庫::class => OboundPolicy::class,
        Outbound::class => OutboundPolicy::class,
        Inventory::class => AlarmPolicy::class,
        Checking_inventory::class => CheckInventPolicy::class,
        Bulletin::class => EditNewsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
