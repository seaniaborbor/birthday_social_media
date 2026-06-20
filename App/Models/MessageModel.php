<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'subject', 'message', 'is_read', 'replied_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;
    
    /**
     * Mark message as read
     */
    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => 1]);
    }
    
    /**
     * Mark message as replied
     */
    public function markAsReplied($id)
    {
        return $this->update($id, ['is_read' => 1, 'replied_at' => date('Y-m-d H:i:s')]);
    }
    
    /**
     * Get unread count
     */
    public function getUnreadCount()
    {
        return $this->where('is_read', 0)->countAllResults();
    }
}