<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KYCDocumentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\PriceHistoryController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\IPOApplicationController;
use App\Http\Controllers\IPOController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserRoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('aboutus');
});

// Authenticated users
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    // Redirect to role-specific dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();

        switch ($user->user_type) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'buyer':
                return redirect()->route('buyer.dashboard');
            case 'seller':
                return redirect()->route('seller.dashboard');
            default:
                abort(403, 'Unauthorized');
        }
    })->name('dashboard');

});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Add more admin routes here
});

// Buyer routes
Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/buyer/dashboard', [BuyerController::class, 'index'])->name('buyer.dashboard');
    // Add more buyer routes here
});

// Seller routes
Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
    // Add more seller routes here
});



// Or define individual routes with custom middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Public routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');



// KYC Documents routes
Route::middleware(['auth'])->group(function () {
    Route::get('/kyc-documents/create', [KYCDocumentController::class, 'create'])->name('kyc-documents.create');
    Route::post('/kyc-documents', [KYCDocumentController::class, 'store'])->name('kyc-documents.store');
    Route::get('/kyc-documents/{document}', [KYCDocumentController::class, 'show'])->name('kyc-documents.show');
    Route::delete('/kyc-documents/{document}', [KYCDocumentController::class, 'destroy'])->name('kyc-documents.destroy');
});

// Admin routes for KYC verification
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/kyc-documents', [KYCDocumentController::class, 'index'])->name('kyc-documents.index');
    Route::post('/kyc-documents/{document}/verify', [KYCDocumentController::class, 'verify'])->name('kyc-documents.verify');
    Route::post('/kyc-documents/{document}/reject', [KYCDocumentController::class, 'reject'])->name('kyc-documents.reject');
});




// Roles routes
Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});




// Permissions routes
Route::middleware(['auth'])->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/search', [PermissionController::class, 'search'])->name('permissions.search');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});




// Role-Permission management routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Main resource routes
    Route::get('/role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
    Route::get('/role-permissions/create', [RolePermissionController::class, 'create'])->name('role-permissions.create');
    Route::post('/role-permissions', [RolePermissionController::class, 'store'])->name('role-permissions.store');
    
    // Role-specific routes
    Route::get('/roles/{role}/permissions', [RolePermissionController::class, 'showByRole'])->name('roles.permissions.show');
    Route::get('/roles/{role}/permissions/edit', [RolePermissionController::class, 'editRolePermissions'])->name('roles.permissions.edit');
    Route::put('/roles/{role}/permissions', [RolePermissionController::class, 'updateRolePermissions'])->name('roles.permissions.update');
    
    // Permission-specific routes
    Route::get('/permissions/{permission}/roles', [RolePermissionController::class, 'showByPermission'])->name('permissions.roles.show');
    
    // Bulk operations
    Route::get('/role-permissions/bulk', [RolePermissionController::class, 'showBulkForm'])->name('role-permissions.bulk');
    Route::post('/role-permissions/bulk', [RolePermissionController::class, 'bulkAssign'])->name('role-permissions.bulk.assign');
    
    // Delete specific permission from role
    Route::delete('/roles/{role}/permissions/{permission}', [RolePermissionController::class, 'destroy'])->name('roles.permissions.destroy');
});




// User-Role management routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Main resource routes
    Route::get('/user-roles', [UserRoleController::class, 'index'])->name('user-roles.index');
    Route::get('/user-roles/create', [UserRoleController::class, 'create'])->name('user-roles.create');
    Route::post('/user-roles', [UserRoleController::class, 'store'])->name('user-roles.store');
    
    // User-specific routes
    Route::get('/users/{user}/roles', [UserRoleController::class, 'showByUser'])->name('users.roles.show');
    Route::get('/users/{user}/roles/edit', [UserRoleController::class, 'editUserRoles'])->name('users.roles.edit');
    Route::put('/users/{user}/roles', [UserRoleController::class, 'updateUserRoles'])->name('users.roles.update');
    
    // Role-specific routes
    Route::get('/roles/{role}/users', [UserRoleController::class, 'showByRole'])->name('roles.users.show');
    
    // Bulk operations
    Route::get('/user-roles/bulk', [UserRoleController::class, 'showBulkForm'])->name('user-roles.bulk');
    Route::post('/user-roles/bulk', [UserRoleController::class, 'bulkAssign'])->name('user-roles.bulk.assign');
    
    // Search
    Route::get('/user-roles/search', [UserRoleController::class, 'searchUsers'])->name('user-roles.search');
    
    // Delete specific role from user
    Route::delete('/users/{user}/roles/{role}', [UserRoleController::class, 'destroy'])->name('users.roles.destroy');
});



// Wallet routes
Route::middleware(['auth'])->group(function () {
    // User wallet management
    Route::get('/my-wallets', [WalletController::class, 'myWallets'])->name('wallets.my-wallets');
    Route::get('/wallets/create', [WalletController::class, 'create'])->name('wallets.create');
    Route::post('/wallets', [WalletController::class, 'store'])->name('wallets.store');
    Route::get('/wallets/{wallet}', [WalletController::class, 'show'])->name('wallets.show');
    Route::delete('/wallets/{wallet}', [WalletController::class, 'destroy'])->name('wallets.destroy');
    
    // Transfer routes
    Route::get('/transfer', [WalletController::class, 'showTransferForm'])->name('wallets.transfer');
    Route::post('/transfer', [WalletController::class, 'transfer'])->name('wallets.transfer.post');
    
    // API routes
    Route::get('/wallets/{wallet}/balance', [WalletController::class, 'getBalance'])->name('wallets.balance');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
    Route::get('/wallets/user/{user}', [WalletController::class, 'getUserWallets'])->name('wallets.user');
    Route::get('/wallets/{wallet}/edit', [WalletController::class, 'edit'])->name('wallets.edit');
    Route::put('/wallets/{wallet}', [WalletController::class, 'update'])->name('wallets.update');
    Route::post('/wallets/{wallet}/lock', [WalletController::class, 'lock'])->name('wallets.lock');
    Route::post('/wallets/{wallet}/unlock', [WalletController::class, 'unlock'])->name('wallets.unlock');
});





// Transaction routes
Route::middleware(['auth'])->group(function () {
    // User transaction management
    Route::get('/my-transactions', [TransactionController::class, 'myTransactions'])->name('transactions.my-transactions');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::post('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.status');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::get('/transactions/statistics', [TransactionController::class, 'statistics'])->name('transactions.statistics');
});





// Payment Method routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
    Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::get('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'show'])->name('payment-methods.show');
    Route::get('/payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::put('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    Route::post('/payment-methods/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');
    Route::get('/payment-methods/configuration-guide/{type}', [PaymentMethodController::class, 'showConfigurationGuide'])->name('payment-methods.configuration-guide');
});

// Public API routes for active payment methods
Route::get('/api/payment-methods/active', [PaymentMethodController::class, 'getActiveMethods'])->name('api.payment-methods.active');




// Asset routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
    Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
    Route::get('/assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
    Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
    Route::put('/assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
    Route::post('/assets/{asset}/toggle-status', [AssetController::class, 'toggleStatus'])->name('assets.toggle-status');
    Route::get('/assets/import', [AssetController::class, 'showImportForm'])->name('assets.import.form');
    Route::post('/assets/import', [AssetController::class, 'import'])->name('assets.import');
    Route::get('/assets/export', [AssetController::class, 'export'])->name('assets.export');
    Route::get('/assets/statistics', [AssetController::class, 'statistics'])->name('assets.statistics');
});

// Public API routes for assets
Route::get('/api/assets', [AssetController::class, 'apiIndex'])->name('api.assets.index');




// Market routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/markets', [MarketController::class, 'index'])->name('markets.index');
    Route::get('/markets/create', [MarketController::class, 'create'])->name('markets.create');
    Route::post('/markets', [MarketController::class, 'store'])->name('markets.store');
    Route::get('/markets/{market}', [MarketController::class, 'show'])->name('markets.show');
    Route::get('/markets/{market}/edit', [MarketController::class, 'edit'])->name('markets.edit');
    Route::put('/markets/{market}', [MarketController::class, 'update'])->name('markets.update');
    Route::delete('/markets/{market}', [MarketController::class, 'destroy'])->name('markets.destroy');
    Route::post('/markets/{market}/toggle-status', [MarketController::class, 'toggleStatus'])->name('markets.toggle-status');
    Route::get('/markets/import', [MarketController::class, 'showImportForm'])->name('markets.import.form');
    Route::post('/markets/import', [MarketController::class, 'import'])->name('markets.import');
    Route::get('/markets/export', [MarketController::class, 'export'])->name('markets.export');
    Route::get('/markets/statistics', [MarketController::class, 'statistics'])->name('markets.statistics');
});

// Public API routes for markets
Route::get('/api/markets', [MarketController::class, 'apiIndex'])->name('api.markets.index');
Route::get('/api/markets/{symbol}', [MarketController::class, 'getBySymbol'])->name('api.markets.symbol');





// Order routes
Route::middleware(['auth'])->group(function () {
    // User order management
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my-orders');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/bulk-cancel', [OrderController::class, 'bulkCancel'])->name('orders.bulk-cancel');
    
    // API endpoints
    Route::get('/api/orders/open', [OrderController::class, 'openOrders'])->name('api.orders.open');
    Route::get('/api/order-book/{market}', [OrderController::class, 'orderBook'])->name('api.order-book');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
});



// Trade routes
Route::middleware(['auth'])->group(function () {
    // User trade management
    Route::get('/my-trades', [TradeController::class, 'myTrades'])->name('trades.my-trades');
    Route::get('/trades/{trade}', [TradeController::class, 'show'])->name('trades.show');
    Route::get('/api/trades/statistics', [TradeController::class, 'userStatistics'])->name('api.trades.statistics');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/trades', [TradeController::class, 'index'])->name('trades.index');
    Route::post('/trades/execute', [TradeController::class, 'executeTrade'])->name('trades.execute');
    Route::get('/trades/export', [TradeController::class, 'export'])->name('trades.export');
    Route::get('/trades/statistics', [TradeController::class, 'platformStatistics'])->name('trades.statistics');
});

// Public API routes
Route::get('/api/trades/history/{market}', [TradeController::class, 'marketHistory'])->name('api.trades.history');





// IPO routes
Route::middleware(['auth'])->group(function () {
    // Public IPO routes
    Route::get('/ipos/public', [IPOController::class, 'public'])->name('ipos.public');
    Route::get('/ipos/{ipo}', [IPOController::class, 'show'])->name('ipos.show');
    Route::get('/my-ipo-subscriptions', [IPOController::class, 'mySubscriptions'])->name('ipos.my-subscriptions');
    
    // Subscription routes
    Route::post('/ipos/{ipo}/subscribe', [IPOController::class, 'subscribe'])->name('ipos.subscribe');
    Route::post('/ipos/{ipo}/cancel-subscription', [IPOController::class, 'cancelSubscription'])->name('ipos.cancel-subscription');
});

// Admin and issue manager routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/ipos', [IPOController::class, 'index'])->name('ipos.index');
    Route::get('/ipos/create', [IPOController::class, 'create'])->name('ipos.create');
    Route::post('/ipos', [IPOController::class, 'store'])->name('ipos.store');
    Route::get('/ipos/{ipo}/edit', [IPOController::class, 'edit'])->name('ipos.edit');
    Route::put('/ipos/{ipo}', [IPOController::class, 'update'])->name('ipos.update');
    Route::post('/ipos/{ipo}/close', [IPOController::class, 'close'])->name('ipos.close');
    Route::post('/ipos/{ipo}/cancel', [IPOController::class, 'cancel'])->name('ipos.cancel');
    Route::get('/ipos/{ipo}/export', [IPOController::class, 'export'])->name('ipos.export');
    Route::get('/ipos/statistics', [IPOController::class, 'statistics'])->name('ipos.statistics');
});




// IPO Application routes
Route::middleware(['auth'])->group(function () {
    // User application routes
    Route::get('/my-ipo-applications', [IPOApplicationController::class, 'myApplications'])->name('ipo-applications.my-applications');
    Route::get('/ipo-applications/create', [IPOApplicationController::class, 'create'])->name('ipo-applications.create');
    Route::post('/ipo-applications', [IPOApplicationController::class, 'store'])->name('ipo-applications.store');
    Route::get('/ipo-applications/{application}', [IPOApplicationController::class, 'show'])->name('ipo-applications.show');
    Route::post('/ipo-applications/{application}/cancel', [IPOApplicationController::class, 'cancel'])->name('ipo-applications.cancel');
});

// Admin and issue manager routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/ipo-applications', [IPOApplicationController::class, 'index'])->name('ipo-applications.index');
    Route::post('/ipo-applications/{application}/allocate', [IPOApplicationController::class, 'allocate'])->name('ipo-applications.allocate');
    Route::post('/ipo-applications/{application}/reject', [IPOApplicationController::class, 'reject'])->name('ipo-applications.reject');
    Route::post('/ipos/{ipo}/bulk-process', [IPOApplicationController::class, 'bulkProcess'])->name('ipo-applications.bulk-process');
    Route::get('/ipo-applications/export', [IPOApplicationController::class, 'export'])->name('ipo-applications.export');
    Route::get('/ipos/{ipo}/application-statistics', [IPOApplicationController::class, 'statistics'])->name('ipo-applications.statistics');
});




// Portfolio routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/portfolio/performance', [PortfolioController::class, 'performance'])->name('portfolio.performance');
    Route::get('/portfolio/{asset}', [PortfolioController::class, 'show'])->name('portfolio.show');
    Route::get('/portfolio/export', [PortfolioController::class, 'export'])->name('portfolio.export');
    
    // Manual entry routes
    Route::get('/portfolio/manual-entry', [PortfolioController::class, 'showManualEntryForm'])->name('portfolio.manual-entry');
    Route::post('/portfolio/manual-entry', [PortfolioController::class, 'addManualEntry'])->name('portfolio.add-manual');
    Route::delete('/portfolio/{portfolio}', [PortfolioController::class, 'removeManualEntry'])->name('portfolio.remove-manual');
});

// API routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/api/portfolio/summary', [PortfolioController::class, 'summary'])->name('api.portfolio.summary');
    Route::get('/api/portfolio/holdings', [PortfolioController::class, 'holdings'])->name('api.portfolio.holdings');
});





// Price History routes
Route::middleware(['auth'])->group(function () {
    // Public routes
    Route::get('/price-history/chart/{asset}', [PriceHistoryController::class, 'chartData'])->name('price-history.chart');
    Route::get('/price-history/historical/{asset}', [PriceHistoryController::class, 'historicalData'])->name('price-history.historical');
    Route::get('/price-history/technical/{asset}', [PriceHistoryController::class, 'technicalIndicators'])->name('price-history.technical');
    Route::get('/price-history/alerts/{asset}', [PriceHistoryController::class, 'priceAlerts'])->name('price-history.alerts');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/price-history', [PriceHistoryController::class, 'index'])->name('price-history.index');
    Route::get('/price-history/create', [PriceHistoryController::class, 'create'])->name('price-history.create');
    Route::post('/price-history', [PriceHistoryController::class, 'store'])->name('price-history.store');
    Route::post('/price-history/import', [PriceHistoryController::class, 'bulkImport'])->name('price-history.import');
});



// Audit Log routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
    Route::get('/audit-logs/statistics', [AuditLogController::class, 'statistics'])->name('audit-logs.statistics');
    Route::get('/audit-logs/cleanup', [AuditLogController::class, 'showCleanupForm'])->name('audit-logs.cleanup.form');
    Route::post('/audit-logs/cleanup', [AuditLogController::class, 'cleanup'])->name('audit-logs.cleanup');
    Route::get('/audit-logs/search', [AuditLogController::class, 'search'])->name('audit-logs.search');
    Route::get('/audit-logs/user-activity/{user}', [AuditLogController::class, 'userActivityReport'])->name('audit-logs.user-activity');
    Route::get('/audit-logs/suspicious-activity', [AuditLogController::class, 'suspiciousActivity'])->name('audit-logs.suspicious-activity');
});

// API routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/api/audit-logs/live-feed', [AuditLogController::class, 'liveFeed'])->name('api.audit-logs.live-feed');
});







// Notification routes
Route::middleware(['auth'])->group(function () {
    // User notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/{notification}/unread', [NotificationController::class, 'markAsUnread'])->name('notifications.mark-unread');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clear-read');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    
    // Preferences
    Route::get('/notifications/preferences', [NotificationController::class, 'getPreferences'])->name('notifications.preferences');
    Route::put('/notifications/preferences', [NotificationController::class, 'updatePreferences'])->name('notifications.update-preferences');
    Route::post('/notifications/test', [NotificationController::class, 'testNotification'])->name('notifications.test');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::post('/notifications/broadcast', [NotificationController::class, 'broadcastToAll'])->name('notifications.broadcast');
    Route::get('/notifications/statistics', [NotificationController::class, 'statistics'])->name('notifications.statistics');
    Route::get('/notifications/export', [NotificationController::class, 'export'])->name('notifications.export');
});

// API routes
Route::middleware(['auth'])->group(function () {
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('api.notifications.unread-count');
    Route::get('/api/notifications/recent', [NotificationController::class, 'recentNotifications'])->name('api.notifications.recent');
});





// System Settings routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/system-settings', [SystemSettingController::class, 'index'])->name('system-settings.index');
    Route::get('/system-settings/create', [SystemSettingController::class, 'create'])->name('system-settings.create');
    Route::post('/system-settings', [SystemSettingController::class, 'store'])->name('system-settings.store');
    Route::get('/system-settings/{systemSetting}', [SystemSettingController::class, 'show'])->name('system-settings.show');
    Route::get('/system-settings/{systemSetting}/edit', [SystemSettingController::class, 'edit'])->name('system-settings.edit');
    Route::put('/system-settings/{systemSetting}', [SystemSettingController::class, 'update'])->name('system-settings.update');
    Route::delete('/system-settings/{systemSetting}', [SystemSettingController::class, 'destroy'])->name('system-settings.destroy');
    Route::post('/system-settings/bulk-update', [SystemSettingController::class, 'bulkUpdate'])->name('system-settings.bulk-update');
    Route::get('/system-settings/import', [SystemSettingController::class, 'import'])->name('system-settings.import.form');
    Route::post('/system-settings/import', [SystemSettingController::class, 'import'])->name('system-settings.import');
    Route::get('/system-settings/export', [SystemSettingController::class, 'export'])->name('system-settings.export');
    Route::post('/system-settings/{systemSetting}/reset', [SystemSettingController::class, 'resetToDefault'])->name('system-settings.reset');
    Route::get('/system-settings/categories', [SystemSettingController::class, 'categories'])->name('system-settings.categories');
    Route::get('/system-settings/category/{category}', [SystemSettingController::class, 'byCategory'])->name('system-settings.by-category');
    Route::get('/system-settings/quick-edit', [SystemSettingController::class, 'quickEdit'])->name('system-settings.quick-edit');
});

// Public API routes for settings
Route::get('/api/settings/{key}', [SystemSettingController::class, 'getSetting'])->name('api.settings.get');
Route::get('/api/settings', [SystemSettingController::class, 'getPublicSettings'])->name('api.settings.public');