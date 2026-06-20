<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'slug', 'description', 'venue', 'event_date', 'event_time',
        'featured_image', 'is_featured', 'status'
    ];
    protected $useTimestamps = true;
    
    /**
     * Get events with RSVP count
     */
    public function getEventsWithRsvpCount($limit = null)
    {
        $builder = $this->db->table('events');
        $builder->select('events.*, COUNT(event_rsvps.id) as rsvp_count');
        $builder->join('event_rsvps', 'event_rsvps.event_id = events.id', 'left');
        $builder->groupBy('events.id');
        $builder->orderBy('events.event_date', 'ASC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get upcoming events
     */
    public function getUpcomingEvents($limit = null)
    {
        $builder = $this->where('event_date >=', date('Y-m-d'))
                        ->where('status', 'upcoming')
                        ->orderBy('event_date', 'ASC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
    
    /**
     * Get event by slug
     */
    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }
    
    /**
     * Generate unique slug
     */
    public function generateSlug($title)
    {
        $slug = url_title($title, '-', true);
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}