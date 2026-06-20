<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'subtitle', 'image', 'button_text', 'button_link', 'sort_order', 'is_active'];
    protected $useTimestamps = true;
    
    /**
     * Get active banners
     */
    public function getActiveBanners()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}