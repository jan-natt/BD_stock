<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\KycDocument;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       

        // 5️⃣ Seeder call (সব একবারে, duplicate call remove করা হলো)
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
            KycDocumentSeeder::class,
            WalletSeeder::class,
            TransactionSeeder::class,
            PaymentMethodSeeder::class,
            AssetSeeder::class,
            MarketSeeder::class,
            OrderSeeder::class,
            TradeSeeder::class,
            IpoSeeder::class,
            IpoApplicationSeeder::class,
            PortfolioSeeder::class,
            PriceHistorySeeder::class,
            AuditLogSeeder::class,
            NotificationSeeder::class,
            SystemSettingSeeder::class,
            ReferralSeeder::class,
            StakingPoolSeeder::class,
            StakingRewardSeeder::class,
        ]);
    }
}
