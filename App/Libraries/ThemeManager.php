<?php

namespace App\Libraries;

class ThemeManager
{
    protected $session;
    protected $defaultTheme = 'light';
    
    public function __construct()
    {
        $this->session = \Config\Services::session();
    }
    
    /**
     * Get current theme mode
     * 
     * @return string
     */
    public function getCurrentTheme()
    {
        $theme = $this->session->get('theme_mode');
        
        if (!$theme) {
            $theme = $this->defaultTheme;
        }
        
        return $theme;
    }
    
    /**
     * Set theme mode
     * 
     * @param string $mode
     * @return bool
     */
    public function setTheme($mode)
    {
        if (!in_array($mode, ['light', 'dark'])) {
            return false;
        }
        
        $this->session->set('theme_mode', $mode);
        return true;
    }
    
    /**
     * Toggle theme mode
     * 
     * @return string
     */
    public function toggleTheme()
    {
        $current = $this->getCurrentTheme();
        $new = $current === 'light' ? 'dark' : 'light';
        $this->setTheme($new);
        
        return $new;
    }
    
    /**
     * Get CSS variables for current theme
     * 
     * @return string
     */
    public function getThemeCssVariables()
    {
        $theme = $this->getCurrentTheme();
        // Use admin-configured colors where available
        $primary = setting('primary_color', '#3b82f6');
        $secondary = setting('secondary_color', '#6b7280');

        // Optional overrides (admins can add these keys if desired)
        $surface = setting('surface_color', $theme === 'dark' ? '#1f2937' : '#ffffff');
        $background = setting('background_color', $theme === 'dark' ? '#111827' : '#f3f4f6');
        $text = setting('text_color', $theme === 'dark' ? '#f3f4f6' : '#111827');
        $textSecondary = setting('text_secondary_color', $theme === 'dark' ? '#9ca3af' : '#4b5563');
        $outline = setting('outline_color', $theme === 'dark' ? '#374151' : '#d1d5db');
        $error = setting('error_color', $theme === 'dark' ? '#ef4444' : '#dc2626');
        $success = setting('success_color', $theme === 'dark' ? '#10b981' : '#059669');

        return "
            :root {
                --color-primary: {$primary};
                --color-secondary: {$secondary};
                --color-surface: {$surface};
                --color-background: {$background};
                --color-text: {$text};
                --color-text-secondary: {$textSecondary};
                --color-outline: {$outline};
                --color-error: {$error};
                --color-success: {$success};
            }
        ";
    }
    
    /**
     * Get font classes based on configuration
     * 
     * @return string
     */
    public function getFontClasses()
    {
        return "font-heading-playfair font-body-merriweather font-mono-courier";
    }
    
    /**
     * Get grain overlay HTML
     * 
     * @return string
     */
    public function getGrainOverlay()
    {
        return '<div class="grain-overlay"></div>';
    }
    
    /**
     * Get polaroid frame HTML for an image
     * 
     * @param string $imageUrl
     * @param string $caption
     * @param string $rotation
     * @return string
     */
    public function getPolaroidFrame($imageUrl, $caption = '', $rotation = '')
    {
        $rotations = ['rotate-1', 'rotate-2', '-rotate-1', '-rotate-2'];
        $rotationClass = $rotation ?: $rotations[array_rand($rotations)];
        
        return '
            <div class="polaroid-frame ' . $rotationClass . '">
                <div class="polaroid-image">
                    <img src="' . $imageUrl . '" alt="' . htmlspecialchars($caption) . '">
                </div>
                ' . ($caption ? '<div class="polaroid-caption">' . htmlspecialchars($caption) . '</div>' : '') . '
                <div class="stamp"></div>
            </div>
        ';
    }
}