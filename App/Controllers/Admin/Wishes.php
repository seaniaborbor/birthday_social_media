<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BirthdayWishModel;
use App\Models\MemberModel;

class Wishes extends BaseController
{
    protected $wishModel;
    protected $memberModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->wishModel = new BirthdayWishModel();
        $this->memberModel = new MemberModel();
    }
    
    /**
     * List all wishes (pending and approved)
     */
    public function index()
    {
        $status = $this->request->getGet('status') ?? 'pending';
        
        if ($status === 'approved') {
            $wishes = $this->wishModel->getApprovedWishes(100);
        } else {
            $wishes = $this->wishModel->getPendingWishes();
        }
        
        $data = [
            'pageTitle' => 'Manage Birthday Wishes',
            'wishes' => $wishes,
            'status' => $status,
            'pendingCount' => $this->wishModel->where('is_approved', 0)->countAllResults(),
            'approvedCount' => $this->wishModel->where('is_approved', 1)->countAllResults()
        ];
        
        return view('admin/wishes/index', $data);
    }
    
    /**
     * Approve a wish
     */
    public function approve($id)
    {
        $wish = $this->wishModel->find($id);
        
        if (!$wish) {
            return redirect()->back()->with('error', 'Wish not found.');
        }
        
        $this->wishModel->update($id, ['is_approved' => 1]);
        
        return redirect()->back()->with('success', 'Wish approved and published to the birthday wall.');
    }
    
    /**
     * Delete a wish
     */
    public function delete($id)
    {
        $wish = $this->wishModel->find($id);
        
        if (!$wish) {
            return redirect()->back()->with('error', 'Wish not found.');
        }
        
        $this->wishModel->delete($id);
        
        return redirect()->back()->with('success', 'Wish deleted successfully.');
    }
    
    /**
     * Bulk approve wishes
     */
    public function bulkApprove()
    {
        $wishIds = $this->request->getPost('wish_ids');
        
        if (empty($wishIds)) {
            return redirect()->back()->with('error', 'No wishes selected.');
        }
        
        foreach ($wishIds as $id) {
            $this->wishModel->update($id, ['is_approved' => 1]);
        }
        
        return redirect()->back()->with('success', count($wishIds) . ' wishes approved successfully.');
    }
}