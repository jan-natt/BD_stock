<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        // Remove all the casts that require missing columns
    ];

    /**
     * Get the decrypted value if encrypted.
     * Modified to always return value since is_encrypted column doesn't exist
     */
    public function getDecryptedValueAttribute()
    {
        return $this->value; // Always return value as-is
    }

    /**
     * Get the typed value based on key or content.
     */
    public function getTypedValueAttribute()
    {
        $value = $this->value;

        // You can implement type detection based on key patterns or content
        if (is_numeric($value)) {
            return (float)$value;
        }
        
        if ($value === 'true' || $value === 'false') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        
        if ($this->isJson($value)) {
            return json_decode($value, true);
        }
        
        return $value;
    }

    /**
     * Check if string is valid JSON
     */
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Scope a query to only include public settings.
     * Modified: Since is_public column doesn't exist, return all or implement alternative logic
     */
    public function scopePublic($query)
    {
        // Option 1: Return all settings (no filtering)
        return $query;
        
        // Option 2: Implement alternative logic if you have a way to identify public settings
        // For example, if public settings have specific key patterns:
        // return $query->where('key', 'like', 'public.%');
    }

    /**
     * Remove all scopes and methods that depend on missing columns
     */
    
    // Remove these methods since they depend on missing columns:
    // scopeOfCategory(), scopeOfType(), scopeEncrypted(), scopeProtected()
    // getOptionsArrayAttribute(), getHasOptionsAttribute(), getValidationRulesArrayAttribute()

    /**
     * Get all settings from cache or database.
     */
    public static function getCached()
    {
        return Cache::remember('system_settings', 3600, function () {
            return self::all()->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->typed_value];
            });
        });
    }

    /**
     * Get public settings from cache or database.
     * Modified to use the updated scopePublic()
     */
    public static function getPublicCached()
    {
        return Cache::remember('public_system_settings', 3600, function () {
            return self::public()->get()->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->typed_value];
            });
        });
    }

    /**
     * Get a specific setting value by key.
     */
    public static function getValue($key, $default = null)
    {
        $settings = self::getCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue($key, $value)
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
            
            // Clear cache
            Cache::forget('system_settings');
            Cache::forget('public_system_settings');
            
            return true;
        }

        return false;
    }

    /**
     * Check if a setting exists.
     */
    public static function has($key)
    {
        $settings = self::getCached();
        return isset($settings[$key]);
    }

    /**
     * Get setting with fallback to default value.
     */
    public static function get($key, $default = null)
    {
        return self::getValue($key, $default);
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            // Clear cache when settings are updated
            Cache::forget('system_settings');
            Cache::forget('public_system_settings');
        });

        static::deleted(function ($model) {
            // Clear cache when settings are deleted
            Cache::forget('system_settings');
            Cache::forget('public_system_settings');
        });
    }

    // Remove the validateValue() method since it depends on missing validation rules
}