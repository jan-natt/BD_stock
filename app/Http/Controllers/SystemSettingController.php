<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SystemSettingController extends Controller
{
    public function __construct()
    {
        // Apply middleware - only admins can manage system settings
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the system settings.
     */
    public function index(Request $request)
    {
        $query = SystemSetting::query();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('value', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Apply type filter
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Sort results
        $sort = $request->get('sort', 'key_asc');
        switch ($sort) {
            case 'key_desc':
                $query->orderBy('key', 'desc');
                break;
            case 'category_asc':
                $query->orderBy('category', 'asc')->orderBy('key', 'asc');
                break;
            case 'category_desc':
                $query->orderBy('category', 'desc')->orderBy('key', 'asc');
                break;
            case 'updated_desc':
                $query->orderBy('updated_at', 'desc');
                break;
            default:
                $query->orderBy('key', 'asc');
        }

        $settings = $query->paginate(50);
        
        $categories = SystemSetting::distinct()->pluck('category')->filter();
        $types = SystemSetting::distinct()->pluck('type')->filter();

        return view('system-settings.index', compact('settings', 'categories', 'types'));
    }

    /**
     * Show the form for creating a new system setting.
     */
    public function create()
    {
        $categories = [
            'general' => 'General',
            'appearance' => 'Appearance',
            'security' => 'Security',
            'email' => 'Email',
            'payment' => 'Payment',
            'trading' => 'Trading',
            'maintenance' => 'Maintenance',
            'api' => 'API',
            'notification' => 'Notification',
            'social' => 'Social Media',
        ];

        $types = [
            'string' => 'String',
            'text' => 'Text',
            'boolean' => 'Boolean',
            'integer' => 'Integer',
            'decimal' => 'Decimal',
            'array' => 'Array',
            'json' => 'JSON',
            'select' => 'Select Options',
        ];

        return view('system-settings.create', compact('categories', 'types'));
    }

    /**
     * Store a newly created system setting.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:system_settings,key',
            'value' => 'required|string',
            'category' => 'required|string|max:100',
            'type' => ['required', Rule::in(['string', 'text', 'boolean', 'integer', 'decimal', 'array', 'json', 'select'])],
            'description' => 'nullable|string|max:500',
            'options' => 'nullable|string|max:1000',
            'is_encrypted' => 'boolean',
            'is_public' => 'boolean',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric|gt:min_value',
            'validation_rules' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Validate value based on type
            $validationErrors = $this->validateSettingValue($validated['type'], $validated['value'], $validated);
            if ($validationErrors) {
                return redirect()->back()
                    ->with('error', $validationErrors)
                    ->withInput();
            }

            // Encrypt value if needed
            if ($validated['is_encrypted'] ?? false) {
                $validated['value'] = encrypt($validated['value']);
            }

            $setting = SystemSetting::create($validated);

            // Clear cache
            $this->clearSettingsCache();

            DB::commit();

            return redirect()->route('system-settings.index')
                ->with('success', 'System setting created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create system setting: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified system setting.
     */
    public function show(SystemSetting $systemSetting)
    {
        return view('system-settings.show', compact('systemSetting'));
    }

    /**
     * Show the form for editing the specified system setting.
     */
    public function edit(SystemSetting $systemSetting)
    {
        $categories = [
            'general' => 'General',
            'appearance' => 'Appearance',
            'security' => 'Security',
            'email' => 'Email',
            'payment' => 'Payment',
            'trading' => 'Trading',
            'maintenance' => 'Maintenance',
            'api' => 'API',
            'notification' => 'Notification',
            'social' => 'Social Media',
        ];

        $types = [
            'string' => 'String',
            'text' => 'Text',
            'boolean' => 'Boolean',
            'integer' => 'Integer',
            'decimal' => 'Decimal',
            'array' => 'Array',
            'json' => 'JSON',
            'select' => 'Select Options',
        ];

        return view('system-settings.edit', compact('systemSetting', 'categories', 'types'));
    }

    /**
     * Update the specified system setting.
     */
    public function update(Request $request, SystemSetting $systemSetting)
    {
        $validated = $request->validate([
            'value' => 'required|string',
            'category' => 'required|string|max:100',
            'type' => ['required', Rule::in(['string', 'text', 'boolean', 'integer', 'decimal', 'array', 'json', 'select'])],
            'description' => 'nullable|string|max:500',
            'options' => 'nullable|string|max:1000',
            'is_encrypted' => 'boolean',
            'is_public' => 'boolean',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric|gt:min_value',
            'validation_rules' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Validate value based on type
            $validationErrors = $this->validateSettingValue($validated['type'], $validated['value'], $validated);
            if ($validationErrors) {
                return redirect()->back()
                    ->with('error', $validationErrors)
                    ->withInput();
            }

            // Handle encryption/decryption
            $currentValue = $systemSetting->value;
            if ($systemSetting->is_encrypted) {
                $currentValue = decrypt($currentValue);
            }

            // Encrypt value if needed
            if ($validated['is_encrypted'] ?? false) {
                $validated['value'] = encrypt($validated['value']);
            } elseif ($systemSetting->is_encrypted) {
                // If changing from encrypted to not encrypted, decrypt the current value
                $validated['value'] = $validated['is_encrypted'] ? encrypt($validated['value']) : $validated['value'];
            }

            $systemSetting->update($validated);

            // Clear cache
            $this->clearSettingsCache();

            // Log the change
            if ($currentValue != $validated['value']) {
                $this->logSettingChange($systemSetting, $currentValue, $validated['value']);
            }

            DB::commit();

            return redirect()->route('system-settings.index')
                ->with('success', 'System setting updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update system setting: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Validate setting value based on type.
     */
    protected function validateSettingValue($type, $value, $validationRules = [])
    {
        switch ($type) {
            case 'boolean':
                if (!in_array(strtolower($value), ['true', 'false', '1', '0', 'yes', 'no'])) {
                    return 'Boolean value must be true/false, 1/0, or yes/no.';
                }
                break;

            case 'integer':
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    return 'Value must be a valid integer.';
                }
                break;

            case 'decimal':
                if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
                    return 'Value must be a valid decimal number.';
                }
                break;

            case 'array':
                if (!is_array(json_decode($value, true)) || json_last_error() !== JSON_ERROR_NONE) {
                    return 'Value must be a valid JSON array.';
                }
                break;

            case 'json':
                if (!is_array(json_decode($value, true)) && !is_object(json_decode($value, true)) || json_last_error() !== JSON_ERROR_NONE) {
                    return 'Value must be a valid JSON.';
                }
                break;

            case 'select':
                if (!empty($validationRules['options'])) {
                    $options = array_map('trim', explode(',', $validationRules['options']));
                    if (!in_array($value, $options)) {
                        return "Value must be one of: " . implode(', ', $options);
                    }
                }
                break;
        }

        // Validate min/max values for numeric types
        if (in_array($type, ['integer', 'decimal'])) {
            $numericValue = (float)$value;
            
            if (isset($validationRules['min_value']) && $numericValue < $validationRules['min_value']) {
                return "Value must be at least {$validationRules['min_value']}.";
            }
            
            if (isset($validationRules['max_value']) && $numericValue > $validationRules['max_value']) {
                return "Value must be at most {$validationRules['max_value']}.";
            }
        }

        // Apply custom validation rules
        if (!empty($validationRules['validation_rules'])) {
            $validator = Validator::make(['value' => $value], [
                'value' => $validationRules['validation_rules']
            ]);

            if ($validator->fails()) {
                return $validator->errors()->first('value');
            }
        }

        return null;
    }

    /**
     * Remove the specified system setting.
     */
    public function destroy(SystemSetting $systemSetting)
    {
        try {
            DB::beginTransaction();

            // Prevent deletion of critical settings
            if ($systemSetting->is_protected) {
                return redirect()->route('system-settings.index')
                    ->with('error', 'Cannot delete protected system setting.');
            }

            $systemSetting->delete();

            // Clear cache
            $this->clearSettingsCache();

            DB::commit();

            return redirect()->route('system-settings.index')
                ->with('success', 'System setting deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('system-settings.index')
                ->with('error', 'Failed to delete system setting: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update system settings.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|exists:system_settings,key',
            'settings.*.value' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $updatedCount = 0;
            foreach ($validated['settings'] as $settingData) {
                $setting = SystemSetting::where('key', $settingData['key'])->first();
                
                if ($setting) {
                    // Validate value based on setting type
                    $validationErrors = $this->validateSettingValue(
                        $setting->type, 
                        $settingData['value'], 
                        [
                            'options' => $setting->options,
                            'min_value' => $setting->min_value,
                            'max_value' => $setting->max_value,
                            'validation_rules' => $setting->validation_rules,
                        ]
                    );

                    if ($validationErrors) {
                        continue; // Skip invalid settings
                    }

                    // Handle encryption
                    $value = $setting->is_encrypted ? encrypt($settingData['value']) : $settingData['value'];
                    
                    $setting->update(['value' => $value]);
                    $updatedCount++;
                }
            }

            // Clear cache
            $this->clearSettingsCache();

            DB::commit();

            return redirect()->route('system-settings.index')
                ->with('success', "Successfully updated {$updatedCount} system settings.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update system settings: ' . $e->getMessage());
        }
    }

    /**
     * Import system settings from JSON file.
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'json_file' => 'required|file|mimes:json|max:2048',
            'overwrite' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('json_file');
            $settings = json_decode(file_get_contents($file->getPathname()), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON file: ' . json_last_error_msg());
            }

            $imported = 0;
            $skipped = 0;
            $updated = 0;

            foreach ($settings as $settingData) {
                // Validate required fields
                if (empty($settingData['key']) || !isset($settingData['value'])) {
                    $skipped++;
                    continue;
                }

                $existingSetting = SystemSetting::where('key', $settingData['key'])->first();

                if ($existingSetting) {
                    if ($validated['overwrite'] ?? false) {
                        // Update existing setting
                        $existingSetting->update([
                            'value' => $settingData['value'],
                            'category' => $settingData['category'] ?? $existingSetting->category,
                            'type' => $settingData['type'] ?? $existingSetting->type,
                            'description' => $settingData['description'] ?? $existingSetting->description,
                        ]);
                        $updated++;
                    } else {
                        $skipped++;
                    }
                } else {
                    // Create new setting
                    SystemSetting::create([
                        'key' => $settingData['key'],
                        'value' => $settingData['value'],
                        'category' => $settingData['category'] ?? 'general',
                        'type' => $settingData['type'] ?? 'string',
                        'description' => $settingData['description'] ?? null,
                        'is_encrypted' => $settingData['is_encrypted'] ?? false,
                        'is_public' => $settingData['is_public'] ?? false,
                    ]);
                    $imported++;
                }
            }

            // Clear cache
            $this->clearSettingsCache();

            DB::commit();

            return redirect()->route('system-settings.index')
                ->with('success', "Settings imported successfully. Imported: {$imported}, Updated: {$updated}, Skipped: {$skipped}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to import settings: ' . $e->getMessage());
        }
    }

    /**
     * Export system settings to JSON file.
     */
    public function export()
    {
        $settings = SystemSetting::all()->map(function($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->is_encrypted ? decrypt($setting->value) : $setting->value,
                'category' => $setting->category,
                'type' => $setting->type,
                'description' => $setting->description,
                'is_encrypted' => $setting->is_encrypted,
                'is_public' => $setting->is_public,
                'options' => $setting->options,
                'min_value' => $setting->min_value,
                'max_value' => $setting->max_value,
                'validation_rules' => $setting->validation_rules,
            ];
        });

        $fileName = 'system-settings-export-' . date('Y-m-d') . '.json';
        
        return response()->json($settings->toArray())
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Get a system setting value by key (API).
     */
    public function getSetting($key)
    {
        $setting = SystemSetting::where('key', $key)->first();

        if (!$setting) {
            return response()->json(['error' => 'Setting not found'], 404);
        }

        if (!$setting->is_public) {
            return response()->json(['error' => 'Setting is not public'], 403);
        }

        $value = $setting->is_encrypted ? decrypt($setting->value) : $setting->value;

        // Convert based on type
        switch ($setting->type) {
            case 'boolean':
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;
            case 'integer':
                $value = (int)$value;
                break;
            case 'decimal':
                $value = (float)$value;
                break;
            case 'array':
            case 'json':
                $value = json_decode($value, true);
                break;
        }

        return response()->json([
            'key' => $setting->key,
            'value' => $value,
            'type' => $setting->type,
            'category' => $setting->category,
        ]);
    }

    /**
     * Get all public system settings (API).
     */
    public function getPublicSettings()
    {
        $settings = SystemSetting::where('is_public', true)
            ->get()
            ->mapWithKeys(function($setting) {
                $value = $setting->is_encrypted ? decrypt($setting->value) : $setting->value;

                // Convert based on type
                switch ($setting->type) {
                    case 'boolean':
                        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        break;
                    case 'integer':
                        $value = (int)$value;
                        break;
                    case 'decimal':
                        $value = (float)$value;
                        break;
                    case 'array':
                    case 'json':
                        $value = json_decode($value, true);
                        break;
                }

                return [$setting->key => $value];
            });

        return response()->json($settings->toArray());
    }

    /**
     * Clear settings cache.
     */
    protected function clearSettingsCache()
    {
        Cache::forget('system_settings');
        Cache::forget('public_system_settings');
    }

    /**
     * Log setting change.
     */
    protected function logSettingChange(SystemSetting $setting, $oldValue, $newValue)
    {
        // You would typically log this to an audit log
        // For example:
        // AuditLogService::log(
        //     'system_setting_updated',
        //     auth()->id(),
        //     [
        //         'key' => $setting->key,
        //         'old_value' => $oldValue,
        //         'new_value' => $newValue,
        //         'category' => $setting->category,
        //     ]
        // );
    }

    /**
     * Reset setting to default value.
     */
    public function resetToDefault(SystemSetting $systemSetting)
    {
        try {
            DB::beginTransaction();

            // You would need to define default values somewhere
            $defaults = $this->getDefaultSettings();
            
            if (isset($defaults[$systemSetting->key])) {
                $defaultValue = $defaults[$systemSetting->key];
                
                // Validate the default value
                $validationErrors = $this->validateSettingValue(
                    $systemSetting->type, 
                    $defaultValue, 
                    [
                        'options' => $systemSetting->options,
                        'min_value' => $systemSetting->min_value,
                        'max_value' => $systemSetting->max_value,
                        'validation_rules' => $systemSetting->validation_rules,
                    ]
                );

                if ($validationErrors) {
                    return redirect()->back()
                        ->with('error', 'Default value validation failed: ' . $validationErrors);
                }

                $value = $systemSetting->is_encrypted ? encrypt($defaultValue) : $defaultValue;
                $systemSetting->update(['value' => $value]);

                // Clear cache
                $this->clearSettingsCache();

                DB::commit();

                return redirect()->back()
                    ->with('success', 'Setting reset to default value successfully.');
            }

            return redirect()->back()
                ->with('error', 'No default value found for this setting.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to reset setting: ' . $e->getMessage());
        }
    }

    /**
     * Get default system settings.
     */
    protected function getDefaultSettings()
    {
        return [
            'app_name' => 'Trading Platform',
            'app_timezone' => 'UTC',
            'app_locale' => 'en',
            'app_currency' => 'USD',
            'maintenance_mode' => 'false',
            'registration_enabled' => 'true',
            'email_verification_required' => 'true',
            'default_user_role' => 'user',
            'max_login_attempts' => '5',
            'session_timeout' => '120',
            // Add more default settings as needed
        ];
    }

    /**
     * Show system settings categories.
     */
    public function categories()
    {
        $categories = SystemSetting::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $categoryStats = [];
        foreach ($categories as $category) {
            $categoryStats[$category->category] = [
                'total' => $category->count,
                'public' => SystemSetting::where('category', $category->category)
                    ->where('is_public', true)
                    ->count(),
                'encrypted' => SystemSetting::where('category', $category->category)
                    ->where('is_encrypted', true)
                    ->count(),
            ];
        }

        return view('system-settings.categories', compact('categoryStats'));
    }

    /**
     * Show settings by category.
     */
    public function byCategory($category)
    {
        $settings = SystemSetting::where('category', $category)
            ->orderBy('key')
            ->get();

        return view('system-settings.by-category', compact('settings', 'category'));
    }

    /**
     * Quick edit form for multiple settings.
     */
    public function quickEdit()
    {
        $categories = SystemSetting::distinct()->pluck('category')->filter();
        $selectedCategory = request('category', $categories->first());

        $settings = SystemSetting::where('category', $selectedCategory)
            ->orderBy('key')
            ->get();

        return view('system-settings.quick-edit', compact('settings', 'categories', 'selectedCategory'));
    }
}