<?php

use App\Models\SettingModel;

/**
 * Get a setting value by key
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        $settingModel = new SettingModel();
        $value = $settingModel->get($key);
        
        if ($value === null) {
            return $default;
        }
        
        return $value;
    }
}

/**
 * Get all settings grouped
 * 
 * @return array
 */
if (!function_exists('get_all_settings')) {
    function get_all_settings()
    {
        $settingModel = new SettingModel();
        return $settingModel->getAllGrouped();
    }
}

/**
 * Get association name
 * 
 * @return string
 */
if (!function_exists('get_association_name')) {
    function get_association_name()
    {
        return setting('association_name', 'Birth Month Association');
    }
}

/**
 * Get configured birth month name
 * 
 * @return string
 */
if (!function_exists('get_birth_month')) {
    function get_birth_month()
    {
        return setting('birth_month', 'September');
    }
}

/**
 * Get configured birth month number
 * 
 * @return int
 */
if (!function_exists('get_birth_month_number')) {
    function get_birth_month_number()
    {
        return (int) setting('birth_month_number', 9);
    }
}

/**
 * Get primary color
 * 
 * @return string
 */
if (!function_exists('get_primary_color')) {
    function get_primary_color()
    {
        return setting('primary_color', '#1d4ed8');
    }
}

/**
 * Get secondary color
 * 
 * @return string
 */
if (!function_exists('get_secondary_color')) {
    function get_secondary_color()
    {
        return setting('secondary_color', '#eab308');
    }
}

/**
 * Check if member is birth month member
 * 
 * @param int $birthMonth
 * @return bool
 */
if (!function_exists('is_birth_month_member')) {
    function is_birth_month_member($birthMonth)
    {
        return $birthMonth == get_birth_month_number();
    }
}

/**
 * Get month name from number
 * 
 * @param int $monthNumber
 * @return string
 */
if (!function_exists('get_month_name')) {
    function get_month_name($monthNumber)
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        return $months[$monthNumber] ?? 'Unknown';
    }
}

/**
 * Format date for vintage display
 * 
 * @param string $date
 * @return string
 */
if (!function_exists('vintage_date')) {
    function vintage_date($date)
    {
        return date('F j, Y', strtotime($date));
    }
}

/**
 * Get asset path with cache busting
 * 
 * @param string $path
 * @return string
 */
if (!function_exists('asset_url')) {
    function asset_url($path)
    {
        $baseURL = rtrim(config('App')->baseURL, '/');
        $timestamp = filemtime(FCPATH . $path);
        return $baseURL . '/' . $path . '?v=' . $timestamp;
    }
}