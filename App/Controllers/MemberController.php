<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\UserRoleModel;
use App\Libraries\BirthdayEngine;

class MemberController extends BaseController
{
    protected $memberModel;
    protected $userRoleModel;
    protected $birthdayEngine;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->userRoleModel = new UserRoleModel();
        $this->birthdayEngine = new BirthdayEngine();
    }

    /**
     * Member Profile Page
     */
    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to view your profile.');
        }

        $memberId = session()->get('memberId');
        $member = $this->memberModel->find($memberId);

        if (!$member) {
            return redirect()->to('/auth/logout')->with('error', 'Member not found.');
        }

        // Get member's roles
        $roles = $this->userRoleModel->getUserRoles($memberId);

        $data = [
            'pageTitle' => 'My Profile',
            'member' => $member,
            'roles' => $roles,
            'isBirthMonthMember' => is_birth_month_member($member['birth_month']),
            'birthMonth' => get_birth_month(),
            'canEditBirthMonth' => setting('admin_override', false)
        ];

        return view('members/profile', $data);
    }

    /**
     * Update Profile
     */
    public function updateProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $memberId = session()->get('memberId');

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'permit_empty|max_length[50]',
            'address' => 'permit_empty|max_length[500]',
            'city' => 'permit_empty|max_length[100]',
            'county' => 'permit_empty|max_length[100]',
            'district' => 'permit_empty|max_length[100]',
            'occupation' => 'permit_empty|max_length[100]',
            'gender' => 'permit_empty|in_list[Male,Female,Other]',
            'profile_photo' => 'permit_empty|is_image[profile_photo]|max_size[profile_photo,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'county' => $this->request->getPost('county'),
            'district' => $this->request->getPost('district'),
            'occupation' => $this->request->getPost('occupation'),
            'gender' => $this->request->getPost('gender')
        ];

        // Handle profile photo upload
        $file = $this->request->getFile('profile_photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Get current member to delete old photo if exists
            $currentMember = $this->memberModel->find($memberId);
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
            $updateData['profile_photo'] = 'uploads/members/' . $newName;
        }

        // Handle birth month update (only if admin override is enabled)
        $adminOverride = setting('admin_override', false);
        if ($adminOverride && $this->request->getPost('birth_month')) {
            $newBirthMonth = (int) $this->request->getPost('birth_month');
            $updateData['birth_month'] = $newBirthMonth;
            $updateData['birth_day'] = $this->request->getPost('birth_day');
            $updateData['birth_year'] = $this->request->getPost('birth_year');
        }

        if ($this->memberModel->update($memberId, $updateData)) {
            // Update session data
            session()->set([
                'firstName' => $updateData['first_name'],
                'lastName' => $updateData['last_name'],
                'fullName' => $updateData['first_name'] . ' ' . $updateData['last_name'],
                'isBirthMonthMember' => is_birth_month_member($updateData['birth_month'] ?? session()->get('birthMonth'))
            ]);

            return redirect()->back()->with('success', 'Profile updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update profile.');
    }

    /**
     * Change Password
     */
    public function changePassword()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }

        $memberId = session()->get('memberId');
        $member = $this->memberModel->find($memberId);

        if (!password_verify($this->request->getPost('current_password'), $member['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $this->memberModel->update($memberId, [
            'password' => $this->request->getPost('new_password')
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    /**
     * View Single Member Profile (Public)
     */
    public function view($id)
    {
        $member = $this->memberModel->find($id);

        if (!$member || !$member['is_approved'] || !$member['is_active']) {
            return redirect()->to('/members/directory')->with('error', 'Member not found.');
        }

        $data = [
            'pageTitle' => $member['first_name'] . ' ' . $member['last_name'],
            'member' => $member,
            'isBirthMonthMember' => is_birth_month_member($member['birth_month'])
        ];

        return view('members/view', $data);
    }

    /**
     * Member Directory (Enhanced with Pagination)
     */
    public function directory()
    {
        $filters = [
            'county' => $this->request->getGet('county'),
            'district' => $this->request->getGet('district'),
            'occupation' => $this->request->getGet('occupation'),
            'gender' => $this->request->getGet('gender'),
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort') ?? 'name'
        ];

        // Remove empty filters
        $filters = array_filter($filters);

        $page = $this->request->getGet('page') ?? 1;
        $perPage = 12;

        $members = $this->memberModel->getFilteredMembersPaginated($filters, $perPage, $page);
        $totalMembers = $this->memberModel->getFilteredMembersCount($filters);

        // Get unique values for filters
        $counties = $this->memberModel->getUniqueCounties();
        $districts = $this->memberModel->getUniqueDistricts();
        $occupations = $this->memberModel->getUniqueOccupations();

        // Calculate pagination
        $totalPages = ceil($totalMembers / $perPage);

        $data = [
            'pageTitle' => 'Member Directory',
            'members' => $members,
            'filters' => $filters,
            'counties' => $counties,
            'districts' => $districts,
            'occupations' => $occupations,
            'totalMembers' => $totalMembers,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'birthMonth' => get_birth_month()
        ];

        return view('members/directory', $data);
    }

    /**
     * Member Directory AJAX alternative (traditional POST for filter updates)
     */
    public function filterDirectory()
    {
        if ($this->request->getMethod() === 'POST') {
            $filters = [
                'county' => $this->request->getPost('county'),
                'district' => $this->request->getPost('district'),
                'occupation' => $this->request->getPost('occupation'),
                'gender' => $this->request->getPost('gender'),
                'search' => $this->request->getPost('search'),
                'sort' => $this->request->getPost('sort') ?? 'name'
            ];

            // Redirect to GET with filters
            return redirect()->to('/members/directory?' . http_build_query(array_filter($filters)));
        }

        return redirect()->to('/members/directory');
    }

    /**
     * Toggle reaction on a profile
     */
    public function react($profileId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to react to profiles.');
        }

        $reactorId = session()->get('memberId');

        // Don't allow reacting to yourself
        if ($reactorId == $profileId) {
            return redirect()->back()->with('error', 'You cannot react to your own profile.');
        }

        $type = $this->request->getPost('type');

        if (!in_array($type, ['like', 'love'])) {
            return redirect()->back()->with('error', 'Invalid reaction type.');
        }

        $reactionModel = new \App\Models\ProfileReactionModel();
        $result = $reactionModel->react($reactorId, $profileId, $type);

        $message = '';
        if ($result['action'] === 'added') {
            $message = $result['type'] === 'like' ? 'You liked this profile!' : 'You loved this profile! ❤️';
        } elseif ($result['action'] === 'removed') {
            $message = 'Reaction removed.';
        } elseif ($result['action'] === 'changed') {
            $message = $result['type'] === 'like' ? 'Changed to like 👍' : 'Changed to love ❤️';
        }

        return redirect()->back()->with('success', $message);
    }

}