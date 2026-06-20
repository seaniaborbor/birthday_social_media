<?php

namespace App\Models;

use CodeIgniter\Model;

class BirthdayWishModel extends Model
{
    protected $table = 'birthday_wishes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['member_id', 'recipient_id', 'recipient_name', 'message', 'is_approved'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;
    
    /**
     * Get approved wishes for display
     * 
     * @param int $limit
     * @return array
     */
    public function getApprovedWishes($limit = 50)
    {
        return $this->select('birthday_wishes.*, members.first_name, members.last_name, members.profile_photo')
                    ->join('members', 'members.id = birthday_wishes.member_id')
                    ->where('birthday_wishes.is_approved', 1)
                    ->orderBy('birthday_wishes.created_at', 'DESC')
                    ->findAll($limit);
    }
    
    /**
     * Get pending wishes for approval
     * 
     * @return array
     */
    public function getPendingWishes()
    {
        return $this->select('birthday_wishes.*, members.first_name, members.last_name')
                    ->join('members', 'members.id = birthday_wishes.member_id')
                    ->where('birthday_wishes.is_approved', 0)
                    ->orderBy('birthday_wishes.created_at', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get wishes for a specific recipient
     * 
     * @param int $recipientId
     * @return array
     */
    public function getWishesForRecipient($recipientId)
    {
        return $this->where('recipient_id', $recipientId)
                    ->where('birthday_wishes.is_approved', 1)
                    ->orderBy('birthday_wishes.created_at', 'DESC')
                    ->findAll();
    }
}