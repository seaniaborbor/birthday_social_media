<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\UserRoleModel;
use App\Models\RoleModel;
use App\Libraries\BirthdayEngine;

class Members extends BaseController
{
    protected $memberModel;
    protected $userRoleModel;
    protected $roleModel;
    protected $birthdayEngine;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->memberModel = new MemberModel();
        $this->userRoleModel = new UserRoleModel();
        $this->roleModel = new RoleModel();
        $this->birthdayEngine = new BirthdayEngine();
    }
    
    /**
     * List all members
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        
        $builder = $this->memberModel;
        
        if ($search) {
            $builder->groupStart()
                    ->like('first_name', $search)
                    ->orLike('last_name', $search)
                    ->orLike('email', $search)
                    ->groupEnd();
        }
        
        if ($status === 'pending') {
            $builder->where('is_approved', 0);
        } elseif ($status === 'approved') {
            $builder->where('is_approved', 1);
        } elseif ($status === 'inactive') {
            $builder->where('is_active', 0);
        }
        
        $totalMembers = $builder->countAllResults(false);
        $members = $builder->orderBy('created_at', 'DESC')
                          ->paginate($perPage, 'default', $page);
        
        $data = [
            'pageTitle' => 'Manage Members',
            'members' => $members,
            'pager' => $this->memberModel->pager,
            'totalMembers' => $totalMembers,
            'search' => $search,
            'status' => $status,
            'pendingCount' => $this->memberModel->where('is_approved', 0)->countAllResults(),
            'approvedCount' => $this->memberModel->where('is_approved', 1)->countAllResults()
        ];
        
        return view('admin/members/index', $data);
    }
    
    /**
     * View member details
     */
    public function view($id)
    {
        $member = $this->memberModel->find($id);
        
        if (!$member) {
            return redirect()->to('/admin/members')->with('error', 'Member not found.');
        }
        
        $roles = $this->userRoleModel->getUserRoles($id);
        $allRoles = $this->roleModel->findAll();
        
        $data = [
            'pageTitle' => 'Member Details',
            'member' => $member,
            'roles' => $roles,
            'allRoles' => $allRoles,
            'isBirthMonthMember' => is_birth_month_member($member['birth_month'])
        ];
        
        return view('admin/members/view', $data);
    }
    
    /**
     * Create new member
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Add New Member',
            'roles' => $this->roleModel->findAll(),
            'birthMonthNumber' => get_birth_month_number()
        ];
        
        return view('admin/members/create', $data);
    }
    
    /**
     * Store new member
     */
    public function store()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[members.email]',
            'password' => 'required|min_length[6]',
            'birth_day' => 'required|numeric|greater_than[0]|less_than[32]',
            'birth_month' => 'required|numeric|greater_than[0]|less_than[13]',
            'birth_year' => 'required|numeric|greater_than[1900]|less_than[' . date('Y') . ']',
            'role_id' => 'permit_empty|numeric',
            'profile_photo' => 'permit_empty|is_image[profile_photo]|max_size[profile_photo,2048]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $memberData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'birth_day' => $this->request->getPost('birth_day'),
            'birth_month' => $this->request->getPost('birth_month'),
            'birth_year' => $this->request->getPost('birth_year'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'county' => $this->request->getPost('county'),
            'district' => $this->request->getPost('district'),
            'occupation' => $this->request->getPost('occupation'),
            'gender' => $this->request->getPost('gender'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'is_approved' => $this->request->getPost('is_approved') ? 1 : 0
        ];
        
        $memberId = $this->memberModel->insert($memberData);
        
        if (!$memberId) {
            return redirect()->back()->withInput()->with('error', 'Failed to create member.');
        }
        
        // Handle profile photo upload
        $file = $this->request->getFile('profile_photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Ensure upload directory exists
            $uploadDir = ROOTPATH . 'public/uploads/members';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename and move file
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $this->memberModel->update($memberId, [
                'profile_photo' => 'uploads/members/' . $newName
            ]);
        }
        
        // Assign role
        $roleId = $this->request->getPost('role_id');
        if ($roleId) {
            $this->userRoleModel->assignRole($memberId, $roleId);
        } else {
            // Assign default member role
            $defaultRole = $this->roleModel->where('name', 'member')->first();
            if ($defaultRole) {
                $this->userRoleModel->assignRole($memberId, $defaultRole['id']);
            }
        }
        
        return redirect()->to('/admin/members')->with('success', 'Member created successfully.');
    }
    
    /**
     * Edit member
     */
    public function edit($id)
    {
        $member = $this->memberModel->find($id);
        
        if (!$member) {
            return redirect()->to('/admin/members')->with('error', 'Member not found.');
        }
        
        $userRoles = $this->userRoleModel->getUserRoles($id);
        $currentRoleId = !empty($userRoles) ? $userRoles[0]['id'] : null;
        
        $data = [
            'pageTitle' => 'Edit Member',
            'member' => $member,
            'roles' => $this->roleModel->findAll(),
            'currentRoleId' => $currentRoleId,
            'birthMonthNumber' => get_birth_month_number()
        ];
        
        return view('admin/members/edit', $data);
    }
    
    /**
     * Update member
     */
    public function update($id)
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email',
            'birth_day' => 'required|numeric|greater_than[0]|less_than[32]',
            'birth_month' => 'required|numeric|greater_than[0]|less_than[13]',
            'birth_year' => 'required|numeric|greater_than[1900]|less_than[' . date('Y') . ']',
            'profile_photo' => 'permit_empty|is_image[profile_photo]|max_size[profile_photo,2048]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $memberData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'birth_day' => $this->request->getPost('birth_day'),
            'birth_month' => $this->request->getPost('birth_month'),
            'birth_year' => $this->request->getPost('birth_year'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'county' => $this->request->getPost('county'),
            'district' => $this->request->getPost('district'),
            'occupation' => $this->request->getPost('occupation'),
            'gender' => $this->request->getPost('gender'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'is_approved' => $this->request->getPost('is_approved') ? 1 : 0
        ];
        
        // Update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $memberData['password'] = $password;
        }
        
        // Handle profile photo upload
        $file = $this->request->getFile('profile_photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Get current member to delete old photo if exists
            $currentMember = $this->memberModel->find($id);
            if ($currentMember && $currentMember['profile_photo']) {
                $oldPhotoPath = ROOTPATH . 'public/' . $currentMember['profile_photo'];
                if (file_exists($oldPhotoPath)) {
                    @unlink($oldPhotoPath);
                }
            }
            
            // Ensure upload directory exists
            $uploadDir = ROOTPATH . 'public/uploads/members';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename and move file
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $memberData['profile_photo'] = 'uploads/members/' . $newName;
        }
        
        if (!$this->memberModel->update($id, $memberData)) {
            return redirect()->back()->withInput()->with('error', 'Failed to update member.');
        }
        
        // Update role
        $roleId = $this->request->getPost('role_id');
        if ($roleId) {
            $this->userRoleModel->assignRole($id, $roleId);
        }
        
        return redirect()->to('/admin/members')->with('success', 'Member updated successfully.');
    }
    
    /**
     * Delete member
     */
    public function delete($id)
    {
        // Don't allow deleting yourself
        if ($id == session()->get('memberId')) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        $member = $this->memberModel->find($id);
        
        if (!$member) {
            return redirect()->back()->with('error', 'Member not found.');
        }
        
        // Delete profile photo if exists
        if ($member['profile_photo'] && file_exists($member['profile_photo'])) {
            unlink($member['profile_photo']);
        }
        
        $this->memberModel->delete($id);
        
        return redirect()->to('/admin/members')->with('success', 'Member deleted successfully.');
    }
    
    /**
     * Approve member
     */
    public function approve($id)
    {
        $member = $this->memberModel->find($id);
        
        if (!$member) {
            return redirect()->back()->with('error', 'Member not found.');
        }
        
        $this->memberModel->update($id, [
            'is_approved' => 1,
            'is_active' => 1
        ]);
        
        // Send approval email
        $this->sendApprovalEmail($member);
        
        return redirect()->back()->with('success', 'Member approved successfully.');
    }
    
    /**
     * Bulk approve members
     */
    public function bulkApprove()
    {
        $memberIds = $this->request->getPost('member_ids');
        
        if (empty($memberIds)) {
            return redirect()->back()->with('error', 'No members selected.');
        }
        
        foreach ($memberIds as $id) {
            $this->memberModel->update($id, [
                'is_approved' => 1,
                'is_active' => 1
            ]);
        }
        
        return redirect()->back()->with('success', count($memberIds) . ' members approved successfully.');
    }
    
    /**
     * Export members to CSV
     */
    public function export()
    {
        $members = $this->memberModel->where('is_approved', 1)->findAll();
        
        $filename = 'members_export_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Birth Date', 'County', 'District', 'Occupation', 'Gender', 'Status', 'Joined Date']);
        
        // Data
        foreach ($members as $member) {
            fputcsv($output, [
                $member['id'],
                $member['first_name'],
                $member['last_name'],
                $member['email'],
                $member['phone'],
                $member['birth_month'] . '/' . $member['birth_day'] . '/' . $member['birth_year'],
                $member['county'],
                $member['district'],
                $member['occupation'],
                $member['gender'],
                $member['is_active'] ? 'Active' : 'Inactive',
                $member['created_at']
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Send approval email
     */
    private function sendApprovalEmail($member)
    {
        $smtpConfigured = setting('smtp_host') && setting('smtp_user') && setting('smtp_pass');
        
        if (!$smtpConfigured) {
            return false;
        }
        
        $email = \Config\Services::email();
        $email->setFrom(setting('smtp_user', 'noreply@bmams.com'), get_association_name());
        $email->setTo($member['email']);
        $email->setSubject('Membership Approved - ' . get_association_name());
        
        $message = "
            <html>
            <body style='font-family: Merriweather, serif;'>
                <h2>Welcome to " . get_association_name() . "!</h2>
                <p>Dear {$member['first_name']} {$member['last_name']},</p>
                <p>Your membership has been approved! You can now log in to your account and access all member features.</p>
                <p><a href='" . base_url('/auth/login') . "' style='background: " . get_primary_color() . "; color: white; padding: 10px 20px; text-decoration: none;'>Login Here</a></p>
                <p>Best regards,<br>" . get_association_name() . "</p>
            </body>
            </html>
        ";
        
        $email->setMessage($message);
        return $email->send();
    }
}