<?php

namespace App\Models;

use CodeIgniter\Model;

class ExecutiveModel extends Model
{
    protected $table = 'executives';
    protected $primaryKey = 'id';
    protected $allowedFields = ['member_id', 'position', 'bio', 'sort_order'];
    protected $useTimestamps = true;
    
    /**
     * Get all executives with member details
     */
    public function getAllWithMembers()
    {
        return $this->select('executives.*, members.first_name, members.last_name, members.profile_photo, members.occupation, members.email')
                    ->join('members', 'members.id = executives.member_id')
                    ->orderBy('executives.sort_order', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get executive by position
     */
    public function getByPosition($position)
    {
        return $this->select('executives.*, members.first_name, members.last_name, members.profile_photo, members.occupation')
                    ->join('members', 'members.id = executives.member_id')
                    ->where('executives.position', $position)
                    ->first();
    }
}