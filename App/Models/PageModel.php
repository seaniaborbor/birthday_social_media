<?php

namespace App\Models;

use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'slug', 'content', 'meta_title', 'meta_description', 'is_published'];
    protected $useTimestamps = true;
    
    /**
     * Generate unique slug
     */
    public function generateSlug($title)
    {
        $slug = url_title($title, '-', true);
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Get published page by slug
     */
    public function getPublishedBySlug($slug)
    {
        return $this->where('slug', $slug)
                    ->where('is_published', 1)
                    ->first();
    }
}