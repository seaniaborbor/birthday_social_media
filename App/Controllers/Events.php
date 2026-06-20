<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\EventRsvpModel;

class Events extends BaseController
{
    protected $eventModel;
    protected $rsvpModel;
    
    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->rsvpModel = new EventRsvpModel();
    }
    
    /**
     * List all events
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 9;
        
        $events = $this->eventModel->where('event_date >=', date('Y-m-d'))
                                   ->where('status !=', 'cancelled')
                                   ->orderBy('event_date', 'ASC')
                                   ->paginate($perPage, 'default', $page);
        
        // Get past events
        $pastEvents = $this->eventModel->where('event_date <', date('Y-m-d'))
                                       ->where('status', 'completed')
                                       ->orderBy('event_date', 'DESC')
                                       ->findAll(5);
        
        $data = [
            'pageTitle' => 'Events',
            'events' => $events,
            'pastEvents' => $pastEvents,
            'pager' => $this->eventModel->pager
        ];
        
        return view('events/index', $data);
    }
    
    /**
     * View single event
     */
    public function view($slug)
    {
        $event = $this->eventModel->getBySlug($slug);
        
        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // Check if user is logged in and has RSVP'd
        $userRsvp = null;
        if (session()->get('isLoggedIn')) {
            $userRsvp = $this->rsvpModel->getMemberStatus($event['id'], session()->get('memberId'));
        }
        
        // Get RSVP counts
        $goingCount = $this->rsvpModel->getCountByEvent($event['id'], 'going');
        $maybeCount = $this->rsvpModel->getCountByEvent($event['id'], 'maybe');
        
        $data = [
            'pageTitle' => $event['title'],
            'event' => $event,
            'userRsvp' => $userRsvp,
            'goingCount' => $goingCount,
            'maybeCount' => $maybeCount
        ];
        
        return view('events/view', $data);
    }
    
    /**
     * RSVP to an event
     */
    public function rsvp($eventId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to RSVP for events.');
        }
        
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found.');
        }
        
        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['going', 'maybe', 'declined'])) {
            return redirect()->back()->with('error', 'Invalid RSVP status.');
        }
        
        $memberId = session()->get('memberId');
        
        // Check if already RSVP'd
        $existingRsvp = $this->rsvpModel->where('event_id', $eventId)
                                        ->where('member_id', $memberId)
                                        ->first();
        
        if ($existingRsvp) {
            // Update existing RSVP
            $this->rsvpModel->update($existingRsvp['id'], ['status' => $status]);
        } else {
            // Create new RSVP
            $this->rsvpModel->insert([
                'event_id' => $eventId,
                'member_id' => $memberId,
                'status' => $status
            ]);
        }
        
        $statusMessages = [
            'going' => "You're going to {$event['title']}!",
            'maybe' => "You might attend {$event['title']}.",
            'declined' => "You declined {$event['title']}."
        ];
        
        return redirect()->back()->with('success', $statusMessages[$status]);
    }
}