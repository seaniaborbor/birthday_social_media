<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BannerModel;

class Banners extends BaseController
{
    protected $bannerModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->bannerModel = new BannerModel();
    }
    
    /**
     * List all banners
     */
    public function index()
    {
        $banners = $this->bannerModel->orderBy('sort_order', 'ASC')->findAll();
        
        $data = [
            'pageTitle' => 'Manage Banners',
            'banners' => $banners,
            'totalBanners' => count($banners)
        ];
        
        return view('admin/banners/index', $data);
    }
    
    /**
     * Create new banner
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Create Banner'
        ];
        
        return view('admin/banners/create', $data);
    }
    
    /**
     * Store new banner
     */
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'subtitle' => 'permit_empty|max_length[255]',
            'button_text' => 'permit_empty|max_length[100]',
            'button_link' => 'permit_empty|max_length[255]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        // Get max sort order
        $maxSort = $this->bannerModel->selectMax('sort_order')->first();
        $sortOrder = ($maxSort['sort_order'] ?? 0) + 1;
        
        $bannerData = [
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'button_text' => $this->request->getPost('button_text'),
            'button_link' => $this->request->getPost('button_link'),
            'sort_order' => $sortOrder,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        // Handle banner image
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'banner_' . time() . '_' . $file->getRandomName();
            $file->move('uploads/banners', $newName);
            $bannerData['image'] = 'uploads/banners/' . $newName;
        } else {
            return redirect()->back()->withInput()->with('error', 'Banner image is required.');
        }
        
        if ($this->bannerModel->insert($bannerData)) {
            return redirect()->to('/admin/banners')->with('success', 'Banner created successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create banner.');
    }
    
    /**
     * Edit banner
     */
    public function edit($id)
    {
        $banner = $this->bannerModel->find($id);
        
        if (!$banner) {
            return redirect()->to('/admin/banners')->with('error', 'Banner not found.');
        }
        
        $data = [
            'pageTitle' => 'Edit Banner',
            'banner' => $banner
        ];
        
        return view('admin/banners/edit', $data);
    }
    
    /**
     * Update banner
     */
    public function update($id)
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'subtitle' => 'permit_empty|max_length[255]',
            'button_text' => 'permit_empty|max_length[100]',
            'button_link' => 'permit_empty|max_length[255]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $banner = $this->bannerModel->find($id);
        
        $bannerData = [
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'button_text' => $this->request->getPost('button_text'),
            'button_link' => $this->request->getPost('button_link'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        // Handle banner image
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old image
            if ($banner['image'] && file_exists($banner['image'])) {
                unlink($banner['image']);
            }
            
            $newName = 'banner_' . time() . '_' . $file->getRandomName();
            $file->move('uploads/banners', $newName);
            $bannerData['image'] = 'uploads/banners/' . $newName;
        }
        
        if ($this->bannerModel->update($id, $bannerData)) {
            return redirect()->to('/admin/banners')->with('success', 'Banner updated successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update banner.');
    }
    
    /**
     * Delete banner
     */
    public function delete($id)
    {
        $banner = $this->bannerModel->find($id);
        
        if (!$banner) {
            return redirect()->back()->with('error', 'Banner not found.');
        }
        
        // Delete image
        if ($banner['image'] && file_exists($banner['image'])) {
            unlink($banner['image']);
        }
        
        $this->bannerModel->delete($id);
        
        return redirect()->to('/admin/banners')->with('success', 'Banner deleted successfully.');
    }
    
    /**
     * Toggle banner active status
     */
    public function toggleActive($id)
    {
        $banner = $this->bannerModel->find($id);
        
        if (!$banner) {
            return redirect()->back()->with('error', 'Banner not found.');
        }
        
        $newStatus = $banner['is_active'] ? 0 : 1;
        $this->bannerModel->update($id, ['is_active' => $newStatus]);
        
        $statusText = $newStatus ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Banner {$statusText} successfully.");
    }
    
    /**
     * Reorder banners
     */
    public function reorder()
    {
        if ($this->request->getMethod() === 'POST') {
            $order = $this->request->getPost('order');
            
            if (!empty($order) && is_array($order)) {
                foreach ($order as $index => $id) {
                    $this->bannerModel->update($id, ['sort_order' => $index + 1]);
                }
                return redirect()->back()->with('success', 'Banner order updated successfully.');
            }
        }
        
        return redirect()->back()->with('error', 'Failed to update order.');
    }
}