<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnnouncementModel;

class Announcements extends BaseController
{
    protected $announcementModel;

    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }

        $this->announcementModel = new AnnouncementModel();
    }

    public function index()
    {
        $announcements = $this->announcementModel->orderBy('created_at', 'DESC')->findAll();

        return view('admin/announcements/index', [
            'pageTitle' => 'Announcements',
            'announcements' => $announcements,
            'totalAnnouncements' => count($announcements),
            'activeAnnouncements' => count(array_filter($announcements, fn ($announcement) => (bool) $announcement['is_active'])),
        ]);
    }

    public function store()
    {
        if ($this->request->getMethod(true) !== 'POST') {
            return redirect()->back();
        }

        $rules = [
            'message' => 'required|min_length[3]',
            'type' => 'required|in_list[info,success,warning,danger]',
            'expires_at' => 'permit_empty|valid_date[Y-m-d\TH:i]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }

        $expiresAt = $this->request->getPost('expires_at');
        if (!empty($expiresAt)) {
            $expiresAt = str_replace('T', ' ', $expiresAt) . ':00';
        } else {
            $expiresAt = null;
        }

        $announcementData = [
            'message' => $this->request->getPost('message'),
            'type' => $this->request->getPost('type'),
            'is_dismissible' => $this->request->getPost('is_dismissible') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'expires_at' => $expiresAt,
        ];

        if ($this->announcementModel->insert($announcementData)) {
            return redirect()->back()->with('success', 'Announcement saved successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to save announcement.');
    }

    public function toggle($id)
    {
        $announcement = $this->announcementModel->find($id);

        if (!$announcement) {
            return redirect()->back()->with('error', 'Announcement not found.');
        }

        $this->announcementModel->update($id, [
            'is_active' => $announcement['is_active'] ? 0 : 1,
        ]);

        return redirect()->back()->with('success', 'Announcement updated successfully.');
    }

    public function delete($id)
    {
        $announcement = $this->announcementModel->find($id);

        if (!$announcement) {
            return redirect()->back()->with('error', 'Announcement not found.');
        }

        $this->announcementModel->delete($id);

        return redirect()->back()->with('success', 'Announcement deleted successfully.');
    }
}
