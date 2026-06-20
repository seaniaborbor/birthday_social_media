<?php

namespace App\Models;

use CodeIgniter\Model;

class EventRsvpModel extends Model
{
    protected $table = 'event_rsvps';
    protected $primaryKey = 'id';
    protected $allowedFields = ['event_id', 'member_id', 'status'];
    protected $useTimestamps = true;
    
    /**
     * Get RSVP count by event
     */
    public function getCountByEvent($eventId, $status = null)
    {
        $builder = $this->where('event_id', $eventId);
        
        if ($status) {
            $builder->where('status', $status);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Get RSVPs with member details
     */
    public function getRsvpsWithMembers($eventId)
    {
        return $this->select('event_rsvps.*, members.first_name, members.last_name, members.email, members.phone')
                    ->join('members', 'members.id = event_rsvps.member_id')
                    ->where('event_rsvps.event_id', $eventId)
                    ->orderBy('event_rsvps.created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Check if member has RSVP'd
     */
    public function hasRsvpd($eventId, $memberId)
    {
        return $this->where('event_id', $eventId)
                    ->where('member_id', $memberId)
                    ->countAllResults() > 0;
    }
    
    /**
     * Get member's RSVP status
     */
    public function getMemberStatus($eventId, $memberId)
    {
        $rsvp = $this->where('event_id', $eventId)
                     ->where('member_id', $memberId)
                     ->first();
        
        return $rsvp ? $rsvp['status'] : null;
    }
}