<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ExecutiveModel;
use App\Models\MemberModel;

class Executives extends BaseController
{
    protected $executiveModel;
    protected $memberModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->executiveModel = new ExecutiveModel();
        $this->memberModel = new MemberModel();
    }
    
    /**
     * List all executives
     */
    public function index()
    {
        $executives = $this->executiveModel->getAllWithMembers();
        
        $data = [
            'pageTitle' => 'Manage Executives',
            'executives' => $executives,
            'totalExecutives' => count($executives)
        ];
        
        return view('admin/executives/index', $data);
    }
    
    /**
     * Create new executive position
     */
    public function create()
    {
        $members = $this->memberModel->where('is_approved', 1)
                                     ->where('is_active', 1)
                                     ->orderBy('first_name', 'ASC')
                                     ->findAll();
        
        $data = [
            'pageTitle' => 'Add Executive Position',
            'members' => $members
        ];
        
        return view('admin/executives/create', $data);
    }
    
    /**
     * Store new executive
     */
    public function store()
    {
        $rules = [
            'member_id' => 'required|numeric|is_not_unique[members.id]',
            'position' => 'required|min_length[3]|max_length[100]',
            'bio' => 'permit_empty|max_length[500]',
            'sort_order' => 'permit_empty|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        // Check if position already exists
        $existing = $this->executiveModel->where('position', $this->request->getPost('position'))->first();
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This position already exists. Please edit the existing position instead.');
        }
        
        $sortOrder = $this->request->getPost('sort_order');
        if (empty($sortOrder)) {
            // Get max sort order and add 1
            $maxSort = $this->executiveModel->selectMax('sort_order')->first();
            $sortOrder = ($maxSort['sort_order'] ?? 0) + 1;
        }
        
        $executiveData = [
            'member_id' => $this->request->getPost('member_id'),
            'position' => $this->request->getPost('position'),
            'bio' => $this->request->getPost('bio'),
            'sort_order' => $sortOrder
        ];
        
        if ($this->executiveModel->insert($executiveData)) {
            return redirect()->to('/admin/executives')->with('success', 'Executive position created successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create executive position.');
    }
    
    /**
     * Edit executive
     */
    public function edit($id)
    {
        $executive = $this->executiveModel->getAllWithMembers($id);
        
        if (!$executive || empty($executive)) {
            return redirect()->to('/admin/executives')->with('error', 'Executive position not found.');
        }
        
        $executive = is_array($executive) ? $executive[0] : $executive;
        
        $members = $this->memberModel->where('is_approved', 1)
                                     ->where('is_active', 1)
                                     ->orderBy('first_name', 'ASC')
                                     ->findAll();
        
        $data = [
            'pageTitle' => 'Edit Executive Position',
            'executive' => $executive,
            'members' => $members
        ];
        
        return view('admin/executives/edit', $data);
    }
    
    /**
     * Update executive
     */
    public function update($id)
    {
        $rules = [
            'member_id' => 'required|numeric|is_not_unique[members.id]',
            'position' => 'required|min_length[3]|max_length[100]',
            'bio' => 'permit_empty|max_length[500]',
            'sort_order' => 'permit_empty|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $executiveData = [
            'member_id' => $this->request->getPost('member_id'),
            'position' => $this->request->getPost('position'),
            'bio' => $this->request->getPost('bio'),
            'sort_order' => $this->request->getPost('sort_order')
        ];
        
        if ($this->executiveModel->update($id, $executiveData)) {
            return redirect()->to('/admin/executives')->with('success', 'Executive position updated successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update executive position.');
    }
    
    /**
     * Delete executive
     */
    public function delete($id)
    {
        $executive = $this->executiveModel->find($id);
        
        if (!$executive) {
            return redirect()->back()->with('error', 'Executive position not found.');
        }
        
        $this->executiveModel->delete($id);
        
        return redirect()->to('/admin/executives')->with('success', 'Executive position deleted successfully.');
    }
    
    /**
     * Reorder executives (AJAX alternative with traditional POST)
     */
    public function reorder()
    {
        if ($this->request->getMethod() === 'POST') {
            $order = $this->request->getPost('order');
            
            if (!empty($order) && is_array($order)) {
                foreach ($order as $index => $id) {
                    $this->executiveModel->update($id, ['sort_order' => $index + 1]);
                }
                return redirect()->back()->with('success', 'Executive order updated successfully.');
            }
        }
        
        return redirect()->back()->with('error', 'Failed to update order.');
    }
}