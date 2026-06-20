<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Settings extends BaseController
{
    protected $settingModel;
    
    public function __construct()
    {
        // Check if user is logged in and is admin
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->settingModel = new SettingModel();
    }
    
    public function index()
    {
        $settings = $this->settingModel->getAllGrouped();
        
        $data = [
            'pageTitle' => 'System Settings',
            'settings' => $settings,
            'birthMonthNumber' => setting('birth_month_number', 9)
        ];
        
        return view('admin/settings/index', $data);
    }
    
    public function update()
    {
        if ($this->request->getMethod(true) !== 'POST') {
            return redirect()->back();
        }
        
        $allSettings = $this->request->getPost();

        // Remove CSRF token from settings. CSRF field name can vary, so
        // remove both the dynamic token name and the legacy 'csrf_test_name'.
        $tokenName = function_exists('csrf_token') ? csrf_token() : null;
        if ($tokenName && isset($allSettings[$tokenName])) {
            unset($allSettings[$tokenName]);
        }
        if (isset($allSettings['csrf_test_name'])) {
            unset($allSettings['csrf_test_name']);
        }
        // Remove method override if present (some clients include _method)
        if (isset($allSettings['_method'])) {
            unset($allSettings['_method']);
        }
        
        // Handle unchecked checkboxes - they're not sent in POST, so we need to set them to 0
        $checkboxSettings = ['admin_override', 'require_approval'];
        foreach ($checkboxSettings as $checkboxName) {
            if (!isset($allSettings[$checkboxName])) {
                $allSettings[$checkboxName] = '0';
            }
        }
        
        foreach ($allSettings as $key => $value) {
            if ($key === 'test_email') {
                continue;
            }

            // Determine the type based on the setting
            $type = $this->getSettingType($key);
            $group = $this->getSettingGroup($key);
            
            // Handle checkbox values (they don't send if unchecked)
            if ($type === 'checkbox') {
                $value = $value ? '1' : '0';
            }
            
            if (!$this->settingModel->upsertSetting($key, $value, $group, $type)) {
                return redirect()->back()->with('error', 'Failed to save one or more settings. Please try again.');
            }
        }
        
        // Clear cache
        cache()->delete('settings_all');
        
        return redirect()->back()->with('success', 'Settings saved successfully!');
    }
    
    public function uploadLogo()
    {
        if ($this->request->getMethod(true) !== 'POST') {
            return redirect()->back();
        }
        
        $file = $this->request->getFile('logo');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'logo_' . time() . '.' . $file->getExtension();
            $file->move('uploads/settings', $newName);
            
            $logoPath = 'uploads/settings/' . $newName;
            $this->settingModel->set('logo', $logoPath, 'branding', 'image');
            
            return redirect()->back()->with('success', 'Logo uploaded successfully!');
        }
        
        return redirect()->back()->with('error', 'Failed to upload logo.');
    }
    
    public function uploadFavicon()
    {
        if ($this->request->getMethod(true) !== 'POST') {
            return redirect()->back();
        }
        
        $file = $this->request->getFile('favicon');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'favicon_' . time() . '.' . $file->getExtension();
            $file->move('uploads/settings', $newName);
            
            $faviconPath = 'uploads/settings/' . $newName;
            $this->settingModel->set('favicon', $faviconPath, 'branding', 'image');
            
            return redirect()->back()->with('success', 'Favicon uploaded successfully!');
        }
        
        return redirect()->back()->with('error', 'Failed to upload favicon.');
    }
    
    public function testEmail()
    {
        if ($this->request->getMethod(true) !== 'POST') {
            return redirect()->back();
        }
        
        $testEmail = $this->request->getPost('test_email');
        
        if (!$testEmail) {
            return redirect()->back()->with('error', 'Please provide a test email address.');
        }
        
        $email = \Config\Services::email();
        
        $email->setFrom(setting('smtp_user', 'noreply@bmams.com'), get_association_name());
        $email->setTo($testEmail);
        $email->setSubject('Test Email from ' . get_association_name());
        $email->setMessage('<html><body><h2>Test Email</h2><p>This is a test email from your association management system.</p><p>SMTP configuration is working correctly!</p></body></html>');
        
        if ($email->send()) {
            return redirect()->back()->with('success', 'Test email sent successfully to ' . $testEmail);
        }
        
        return redirect()->back()->with('error', 'Failed to send test email. Please check your SMTP settings.');
    }
    
    private function getSettingType($key)
    {
        $types = [
            'association_name' => 'text',
            'birth_month' => 'text',
            'birth_month_number' => 'number',
            'motto' => 'text',
            'association_email' => 'email',
            'association_phone' => 'text',
            'association_address' => 'textarea',
            'primary_color' => 'color',
            'secondary_color' => 'color',
            'logo' => 'image',
            'favicon' => 'image',
            'smtp_host' => 'text',
            'smtp_port' => 'number',
            'smtp_user' => 'text',
            'smtp_pass' => 'password',
            'smtp_encryption' => 'select',
            'site_title' => 'text',
            'site_description' => 'textarea',
            'site_keywords' => 'text',
            'facebook_url' => 'url',
            'twitter_url' => 'url',
            'instagram_url' => 'url',
            'linkedin_url' => 'url',
            'admin_override' => 'checkbox',
            'require_approval' => 'checkbox',
            'membership_target' => 'number',
            'contact_map_embed' => 'textarea',
            'contact_latitude' => 'text',
            'contact_longitude' => 'text'
        ];
        
        return $types[$key] ?? 'text';
    }
    
    private function getSettingGroup($key)
    {
        $groups = [
            'association_name' => 'general',
            'birth_month' => 'general',
            'birth_month_number' => 'general',
            'motto' => 'general',
            'association_email' => 'general',
            'association_phone' => 'general',
            'association_address' => 'general',
            'primary_color' => 'branding',
            'secondary_color' => 'branding',
            'logo' => 'branding',
            'favicon' => 'branding',
            'smtp_host' => 'smtp',
            'smtp_port' => 'smtp',
            'smtp_user' => 'smtp',
            'smtp_pass' => 'smtp',
            'smtp_encryption' => 'smtp',
            'site_title' => 'seo',
            'site_description' => 'seo',
            'site_keywords' => 'seo',
            'facebook_url' => 'social',
            'twitter_url' => 'social',
            'instagram_url' => 'social',
            'linkedin_url' => 'social',
            'admin_override' => 'security',
            'require_approval' => 'security',
            'membership_target' => 'general',
            'contact_map_embed' => 'contact',
            'contact_latitude' => 'contact',
            'contact_longitude' => 'contact'
        ];
        
        return $groups[$key] ?? 'general';
    }
}
