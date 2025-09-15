<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Role;
use App\Policies\RolePolicy;
use App\Models\Permission;
use App\Policies\PermissionPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Wallet;
use App\Policies\WalletPolicy;
use App\Models\Transaction;
use App\Policies\TransactionPolicy;
use App\Models\PaymentMethod;
use App\Policies\PaymentMethodPolicy;
use App\Models\Asset;
use App\Policies\AssetPolicy;
use App\Models\Market;
use App\Policies\MarketPolicy;
use App\Models\Order;
use App\Policies\OrderPolicy;
use App\Models\Trade;
use App\Policies\TradePolicy;
use App\Models\IPO;
use App\Policies\IPOPolicy;
use App\Models\IPOApplication;
use App\Policies\IPOApplicationPolicy;
use App\Models\Portfolio;
use App\Policies\PortfolioPolicy;
use App\Models\PriceHistory;
use App\Policies\PriceHistoryPolicy;
use App\Models\AuditLog;
use App\Policies\AuditLogPolicy;
use App\Models\Notification;
use App\Policies\NotificationPolicy;
use App\Models\SystemSetting;
use App\Policies\SystemSettingPolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Role::class => RolePolicy::class,
     Permission::class => PermissionPolicy::class,
      User::class => UserPolicy::class,
    Role::class => RolePolicy::class,
    Wallet::class => WalletPolicy::class,
    Transaction::class => TransactionPolicy::class,
     PaymentMethod::class => PaymentMethodPolicy::class,
       Asset::class => AssetPolicy::class,
        Market::class => MarketPolicy::class,
        Order::class => OrderPolicy::class,
         Trade::class => TradePolicy::class,
          IPO::class => IPOPolicy::class,
           IPOApplication::class => IPOApplicationPolicy::class,
              Portfolio::class => PortfolioPolicy::class,
              PriceHistory::class => PriceHistoryPolicy::class,
               AuditLog::class => AuditLogPolicy::class,
                Notification::class => NotificationPolicy::class,
                 SystemSetting::class => SystemSettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
