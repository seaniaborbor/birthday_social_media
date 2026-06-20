<?php

namespace App\Controllers;

use App\Libraries\ThemeManager;

class Theme extends BaseController
{
    protected $themeManager;
    
    public function __construct()
    {
        $this->themeManager = new ThemeManager();
    }
    
    public function toggle()
    {
        if ($this->request->getMethod() === 'POST') {
            $newTheme = $this->themeManager->toggleTheme();
            
            // Store in session for immediate use
            session()->set('theme_mode', $newTheme);
        }
        
        // Redirect back
        return redirect()->back();
    }
}