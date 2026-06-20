<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['message', 'type', 'is_dismissible', 'is_active', 'expires_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;
    
    /**
     * Get active announcements
     */
    public function getActiveAnnouncements()
    {
        return $this->where('is_active', 1)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->orWhere('expires_at IS NULL')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Dismiss announcement (store in session)
     */
    public function dismiss($announcementId, $userId = null)
    {
        $session = \Config\Services::session();
        $dismissed = $session->get('dismissed_announcements') ?? [];
        
        if (!in_array($announcementId, $dismissed)) {
            $dismissed[] = $announcementId;
            $session->set('dismissed_announcements', $dismissed);
        }
        
        return true;
    }
    
    /**
     * Get non-dismissed announcements
     */
    public function getNonDismissed($userId = null)
    {
        $session = \Config\Services::session();
        $dismissed = $session->get('dismissed_announcements') ?? [];
        
        $builder = $this->where('is_active', 1)
                        ->where('expires_at >', date('Y-m-d H:i:s'))
                        ->orWhere('expires_at IS NULL')
                        ->orderBy('created_at', 'DESC');
        
        $announcements = $builder->findAll();
        
        // Filter out dismissed ones
        $filtered = [];
        foreach ($announcements as $announcement) {
            if (!in_array($announcement['id'], $dismissed)) {
                $filtered[] = $announcement;
            }
        }
        
        return $filtered;
    }
}