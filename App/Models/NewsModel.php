<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'category', 'views', 'is_published', 'published_at'
    ];
    protected $useTimestamps = true;
    
    /**
     * Get published news articles
     */
    public function getPublishedNews($limit = null)
    {
        $builder = $this->where('is_published', 1)
                        ->where('published_at <=', date('Y-m-d H:i:s'))
                        ->orderBy('published_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
    
    /**
     * Get news by slug
     */
    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }
    
    /**
     * Increment view count
     */
    public function incrementViews($id)
    {
        return $this->set('views', 'views+1', false)->where('id', $id)->update();
    }
    
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
}