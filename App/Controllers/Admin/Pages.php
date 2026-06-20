<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageModel;

class Pages extends BaseController
{
    protected $pageModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->pageModel = new PageModel();
    }
    
    /**
     * List all pages
     */
    public function index()
    {
        $pages = $this->pageModel->orderBy('created_at', 'DESC')->findAll();
        
        $data = [
            'pageTitle' => 'Manage Pages',
            'pages' => $pages,
            'totalPages' => count($pages)
        ];
        
        return view('admin/pages/index', $data);
    }
    
    /**
     * Create new page
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Create Page'
        ];
        
        return view('admin/pages/create', $data);
    }
    
    /**
     * Store new page
     */
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]',
            'slug' => 'permit_empty|alpha_dash|is_unique[pages.slug]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $slug = $this->request->getPost('slug');
        if (empty($slug)) {
            $slug = $this->pageModel->generateSlug($this->request->getPost('title'));
        }
        
        $pageData = [
            'title' => $this->request->getPost('title'),
            'slug' => $slug,
            'content' => $this->request->getPost('content'),
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'is_published' => $this->request->getPost('is_published') ? 1 : 0
        ];
        
        if ($this->pageModel->insert($pageData)) {
            return redirect()->to('/admin/pages')->with('success', 'Page created successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create page.');
    }
    
    /**
     * Edit page
     */
    public function edit($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            return redirect()->to('/admin/pages')->with('error', 'Page not found.');
        }
        
        $data = [
            'pageTitle' => 'Edit Page',
            'page' => $page
        ];
        
        return view('admin/pages/edit', $data);
    }
    
    /**
     * Update page
     */
    public function update($id)
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]'
        ];
        
        $page = $this->pageModel->find($id);
        $slug = $this->request->getPost('slug');
        
        if ($slug && $slug != $page['slug']) {
            $rules['slug'] = 'is_unique[pages.slug]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $pageData = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'is_published' => $this->request->getPost('is_published') ? 1 : 0
        ];
        
        if ($slug && $slug != $page['slug']) {
            $pageData['slug'] = $slug;
        }
        
        if ($this->pageModel->update($id, $pageData)) {
            return redirect()->to('/admin/pages')->with('success', 'Page updated successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update page.');
    }
    
    /**
     * Delete page
     */
    public function delete($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            return redirect()->back()->with('error', 'Page not found.');
        }
        
        // Don't allow deleting important system pages
        $protectedSlugs = ['about', 'privacy', 'terms'];
        if (in_array($page['slug'], $protectedSlugs)) {
            return redirect()->back()->with('error', 'Cannot delete system page.');
        }
        
        $this->pageModel->delete($id);
        
        return redirect()->to('/admin/pages')->with('success', 'Page deleted successfully.');
    }
    
    /**
     * Toggle publish status
     */
    public function togglePublish($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            return redirect()->back()->with('error', 'Page not found.');
        }
        
        $newStatus = $page['is_published'] ? 0 : 1;
        $this->pageModel->update($id, ['is_published' => $newStatus]);
        
        $statusText = $newStatus ? 'published' : 'unpublished';
        return redirect()->back()->with('success', "Page {$statusText} successfully.");
    }
}