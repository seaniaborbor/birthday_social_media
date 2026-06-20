<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NewsModel;

class News extends BaseController
{
    protected $newsModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->newsModel = new NewsModel();
    }
    
    /**
     * List all news articles
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 15;
        $category = $this->request->getGet('category');
        $status = $this->request->getGet('status');
        
        $builder = $this->newsModel;
        
        if ($category && $category !== 'all') {
            $builder->where('category', $category);
        }
        
        if ($status === 'published') {
            $builder->where('is_published', 1);
        } elseif ($status === 'draft') {
            $builder->where('is_published', 0);
        }
        
        $totalNews = $builder->countAllResults(false);
        $news = $builder->orderBy('created_at', 'DESC')
                        ->paginate($perPage, 'default', $page);
        
        // Get all categories for filter
        $categories = $this->newsModel->select('category')->distinct()->findAll();
        
        $data = [
            'pageTitle' => 'Manage News',
            'news' => $news,
            'pager' => $this->newsModel->pager,
            'totalNews' => $totalNews,
            'categories' => $categories,
            'category' => $category,
            'status' => $status,
            'publishedCount' => $this->newsModel->where('is_published', 1)->countAllResults(),
            'draftCount' => $this->newsModel->where('is_published', 0)->countAllResults()
        ];
        
        return view('admin/news/index', $data);
    }
    
    /**
     * Create new news article
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Create News Article'
        ];
        
        return view('admin/news/create', $data);
    }
    
    /**
     * Store new news article
     */
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[20]',
            'excerpt' => 'permit_empty|max_length[500]',
            'category' => 'permit_empty|max_length[100]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        // Generate slug
        $slug = $this->newsModel->generateSlug($this->request->getPost('title'));
        
        $newsData = [
            'title' => $this->request->getPost('title'),
            'slug' => $slug,
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'category' => $this->request->getPost('category') ?: 'General',
            'is_published' => $this->request->getPost('is_published') ? 1 : 0
        ];
        
        // Set published date if published
        if ($newsData['is_published']) {
            $newsData['published_at'] = date('Y-m-d H:i:s');
        }
        
        // Handle featured image upload
        $file = $this->request->getFile('featured_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'news_' . time() . '_' . $file->getRandomName();
            $file->move('uploads/news', $newName);
            $newsData['featured_image'] = 'uploads/news/' . $newName;
        }
        
        if ($this->newsModel->insert($newsData)) {
            return redirect()->to('/admin/news')->with('success', 'News article created successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create news article.');
    }
    
    /**
     * Edit news article
     */
    public function edit($id)
    {
        $news = $this->newsModel->find($id);
        
        if (!$news) {
            return redirect()->to('/admin/news')->with('error', 'News article not found.');
        }
        
        $data = [
            'pageTitle' => 'Edit News Article',
            'news' => $news
        ];
        
        return view('admin/news/edit', $data);
    }
    
    /**
     * Update news article
     */
    public function update($id)
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[20]',
            'excerpt' => 'permit_empty|max_length[500]',
            'category' => 'permit_empty|max_length[100]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $newsData = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'category' => $this->request->getPost('category') ?: 'General',
            'is_published' => $this->request->getPost('is_published') ? 1 : 0
        ];
        
        // Update slug if title changed
        $news = $this->newsModel->find($id);
        if ($news['title'] !== $this->request->getPost('title')) {
            $newsData['slug'] = $this->newsModel->generateSlug($this->request->getPost('title'));
        }
        
        // Set published date if being published now
        if ($newsData['is_published'] && !$news['is_published']) {
            $newsData['published_at'] = date('Y-m-d H:i:s');
        }
        
        // Handle featured image upload
        $file = $this->request->getFile('featured_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old image
            if ($news['featured_image'] && file_exists($news['featured_image'])) {
                unlink($news['featured_image']);
            }
            
            $newName = 'news_' . time() . '_' . $file->getRandomName();
            $file->move('uploads/news', $newName);
            $newsData['featured_image'] = 'uploads/news/' . $newName;
        }
        
        if ($this->newsModel->update($id, $newsData)) {
            return redirect()->to('/admin/news')->with('success', 'News article updated successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update news article.');
    }
    
    /**
     * Delete news article
     */
    public function delete($id)
    {
        $news = $this->newsModel->find($id);
        
        if (!$news) {
            return redirect()->back()->with('error', 'News article not found.');
        }
        
        // Delete featured image
        if ($news['featured_image'] && file_exists($news['featured_image'])) {
            unlink($news['featured_image']);
        }
        
        $this->newsModel->delete($id);
        
        return redirect()->to('/admin/news')->with('success', 'News article deleted successfully.');
    }
    
    /**
     * Toggle publish status
     */
    public function togglePublish($id)
    {
        $news = $this->newsModel->find($id);
        
        if (!$news) {
            return redirect()->back()->with('error', 'News article not found.');
        }
        
        $newStatus = $news['is_published'] ? 0 : 1;
        $updateData = ['is_published' => $newStatus];
        
        if ($newStatus) {
            $updateData['published_at'] = date('Y-m-d H:i:s');
        }
        
        $this->newsModel->update($id, $updateData);
        
        $statusText = $newStatus ? 'published' : 'unpublished';
        return redirect()->back()->with('success', "News article {$statusText} successfully.");
    }
}