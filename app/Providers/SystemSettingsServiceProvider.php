<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SystemSetting;

class SystemSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share system settings with all views
        view()->composer('*', function ($view) {
            $view->with('systemSettings', SystemSetting::getPublicCached());
        });

        // Set configuration values from system settings
        try {
            $settings = SystemSetting::getCached();

            // Set application settings
            config([
                'app.name' => $settings['app_name'] ?? config('app.name'),
                'app.timezone' => $settings['app_timezone'] ?? config('app.timezone'),
                'app.locale' => $settings['app_locale'] ?? config('app.locale'),
            ]);

            // Set mail settings
            if (isset($settings['mail_host'])) {
                config([
                    'mail.mailers.smtp.host' => $settings['mail_host'],
                    'mail.mailers.smtp.port' => $settings['mail_port'] ?? 587,
                    'mail.mailers.smtp.username' => $settings['mail_username'],
                    'mail.mailers.smtp.password' => $settings['mail_password'],
                    'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? 'tls',
                    'mail.from.address' => $settings['mail_from_address'],
                    'mail.from.name' => $settings['mail_from_name'],
                ]);
            }

        } catch (\Exception $e) {
            // Handle exception (e.g., database not available during installation)
        }
    }
}