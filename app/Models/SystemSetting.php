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
        'category',
        'type',
        'description',
        'options',
        'is_encrypted',
        'is_public',
        'is_protected',
        'min_value',
        'max_value',
        'validation_rules',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
        'is_public' => 'boolean',
        'is_protected' => 'boolean',
        'min_value' => 'decimal:8',
        'max_value' => 'decimal:8',
    ];

    /**
     * Get the decrypted value if encrypted.
     */
    public function getDecryptedValueAttribute()
    {
        return $this->is_encrypted ? decrypt($this->value) : $this->value;
    }

    /**
     * Get the typed value based on type.
     */
    public function getTypedValueAttribute()
    {
        $value = $this->decrypted_value;

        switch ($this->type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int)$value;
            case 'decimal':
                return (float)$value;
            case 'array':
            case 'json':
                return json_decode($value, true);
            case 'select':
                return $value; // Return as string for select
            default:
                return $value;
        }
    }

    /**
     * Scope a query to only include public settings.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope a query to only include settings of a specific category.
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include settings of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include encrypted settings.
     */
    public function scopeEncrypted($query)
    {
        return $query->where('is_encrypted', true);
    }

    /**
     * Scope a query to only include protected settings.
     */
    public function scopeProtected($query)
    {
        return $query->where('is_protected', true);
    }

    /**
     * Get options as array for select type.
     */
    public function getOptionsArrayAttribute()
    {
        if (empty($this->options)) {
            return [];
        }

        return array_map('trim', explode(',', $this->options));
    }

    /**
     * Check if setting has options.
     */
    public function getHasOptionsAttribute()
    {
        return !empty($this->options);
    }

    /**
     * Get validation rules array.
     */
    public function getValidationRulesArrayAttribute()
    {
        if (empty($this->validation_rules)) {
            return [];
        }

        return explode('|', $this->validation_rules);
    }

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
     * Get all settings of a category.
     */
    public static function getCategory($category)
    {
        $settings = self::getCached();
        return collect($settings)->filter(function ($value, $key) use ($category) {
            $setting = self::where('key', $key)->first();
            return $setting && $setting->category === $category;
        });
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Validate value based on type before saving
            $model->validateValue();
        });

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

    /**
     * Validate the setting value based on type.
     */
    public function validateValue()
    {
        // This would implement validation logic similar to the controller method
        // You can use Laravel's validator here
    }
}