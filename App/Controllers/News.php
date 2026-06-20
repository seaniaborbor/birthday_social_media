<?php

namespace App\Controllers;

use App\Models\NewsModel;

class News extends BaseController
{
    protected $newsModel;
    
    public function __construct()
    {
        $this->newsModel = new NewsModel();
    }
    
    /**
     * List all published news
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;
        $category = $this->request->getGet('category');
        
        $builder = $this->newsModel->where('is_published', 1)
                                   ->where('published_at <=', date('Y-m-d H:i:s'));
        
        if ($category) {
            $builder->where('category', $category);
        }
        
        $news = $builder->orderBy('published_at', 'DESC')
                        ->paginate($perPage, 'default', $page);
        
        // Get recent news for sidebar
        $recentNews = $this->newsModel->where('is_published', 1)
                                      ->where('published_at <=', date('Y-m-d H:i:s'))
                                      ->orderBy('published_at', 'DESC')
                                      ->findAll(5);
        
        // Get all categories
        $categories = $this->newsModel->select('category')
                                      ->distinct()
                                      ->where('is_published', 1)
                                      ->findAll();
        
        $data = [
            'pageTitle' => 'News',
            'news' => $news,
            'pager' => $this->newsModel->pager,
            'recentNews' => $recentNews,
            'categories' => $categories,
            'currentCategory' => $category
        ];
        
        return view('news/index', $data);
    }
    
    /**
     * View single news article
     */
    public function view($slug)
    {
        $news = $this->newsModel->where('slug', $slug)->first();
        
        if (!$news || !$news['is_published']) {
            return redirect()->to('/news')->with('error', 'News article not found.');
        }
        
        // Increment view count
        $this->newsModel->incrementViews($news['id']);
        
        // Get related news (same category)
        $relatedNews = $this->newsModel->where('category', $news['category'])
                                       ->where('id !=', $news['id'])
                                       ->where('is_published', 1)
                                       ->orderBy('published_at', 'DESC')
                                       ->findAll(3);
        
        $data = [
            'pageTitle' => $news['title'],
            'news' => $news,
            'relatedNews' => $relatedNews
        ];
        
        return view('news/view', $data);
    }
}