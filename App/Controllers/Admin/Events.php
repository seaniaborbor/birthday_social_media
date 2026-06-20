<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\EventRsvpModel;

class Events extends BaseController
{
    protected $eventModel;
    protected $rsvpModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->eventModel = new EventModel();
        $this->rsvpModel = new EventRsvpModel();
    }
    
    /**
     * List all events
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 15;
        $status = $this->request->getGet('status');
        
        $builder = $this->eventModel;
        
        if ($status && $status !== 'all') {
            $builder->where('status', $status);
        }
        
        $totalEvents = $builder->countAllResults(false);
        $events = $builder->orderBy('event_date', 'DESC')
                          ->paginate($perPage, 'default', $page);
        
        // Get RSVP counts for each event
        foreach ($events as &$event) {
            $event['rsvp_count'] = $this->rsvpModel->getCountByEvent($event['id']);
        }
        
        $data = [
            'pageTitle' => 'Manage Events',
            'events' => $events,
            'pager' => $this->eventModel->pager,
            'totalEvents' => $totalEvents,
            'status' => $status,
            'upcomingCount' => $this->eventModel->where('status', 'upcoming')->where('event_date >=', date('Y-m-d'))->countAllResults(),
            'completedCount' => $this->eventModel->where('status', 'completed')->countAllResults()
        ];
        
        return view('admin/events/index', $data);
    }
    
    /**
     * Create new event
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Create Event'
        ];
        
        return view('admin/events/create', $data);
    }
    
    /**
     * Store new event
     */
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required|min_length[10]',
            'venue' => 'required|min_length[3]|max_length[255]',
            'event_date' => 'required|valid_date',
            'event_time' => 'permit_empty',
            'status' => 'required|in_list[upcoming,ongoing,completed,cancelled]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        // Generate slug
        $slug = $this->eventModel->generateSlug($this->request->getPost('title'));
        
        $eventData = [
            'title' => $this->request->getPost('title'),
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
            'venue' => $this->request->getPost('venue'),
            'event_date' => $this->request->getPost('event_date'),
            'event_time' => $this->request->getPost('event_time'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status')
        ];
        
        // Handle featured image upload
        $file = $this->request->getFile('featured_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'event_' . time() . '_' . $file->getRandomName();
            $file->move('uploads/events', $newName);
            $eventData['featured_image'] = 'uploads/events/' . $newName;
        }
        
        if ($this->eventModel->insert($eventData)) {
            return redirect()->to('/admin/events')->with('success', 'Event created successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create event.');
    }
    
    /**
     * Edit event
     */
    public function edit($id)
    {
        $event = $this->eventModel->find($id);
        
        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event not found.');
        }
        
        $data = [
            'pageTitle' => 'Edit Event',
            'event' => $event
        ];
        
        return view('admin/events/edit', $data);
    }
    
    /**
     * Update event
     */
    public function update($id)
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required|min_length[10]',
            'venue' => 'required|min_length[3]|max_length[255]',
            'event_date' => 'required|valid_date',
            'event_time' => 'permit_empty',
            'status' => 'required|in_list[upcoming,ongoing,completed,cancelled]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $eventData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'venue' => $this->request->getPost('venue'),
            'event_date' => $this->request->getPost('event_date'),
            'event_time' => $this->request->getPost('event_time'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'status' => $this->request->getPost('status')
        ];
        
        // Update slug if title changed
        $event = $this->eventModel->find($id);
        if ($event['title'] !== $this->request->getPost('title')) {
            $eventData['slug'] = $this->eventModel->generateSlug($this->request->getPost('title'));
        }
        
        // Handle featured image upload
        $file = $this->request->getFile('featured_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old image
            if ($event['featured_image'] && file_exists($event['featured_image'])) {
                unlink($event['featured_image']);
            }
            
            $newName = 'event_' . time() . '_' . $file->getRandomName();
            $file->move('uploads/events', $newName);
            $eventData['featured_image'] = 'uploads/events/' . $newName;
        }
        
        if ($this->eventModel->update($id, $eventData)) {
            return redirect()->to('/admin/events')->with('success', 'Event updated successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update event.');
    }
    
    /**
     * Delete event
     */
    public function delete($id)
    {
        $event = $this->eventModel->find($id);
        
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found.');
        }
        
        // Delete featured image
        if ($event['featured_image'] && file_exists($event['featured_image'])) {
            unlink($event['featured_image']);
        }
        
        // Delete all RSVPs
        $this->rsvpModel->where('event_id', $id)->delete();
        
        $this->eventModel->delete($id);
        
        return redirect()->to('/admin/events')->with('success', 'Event deleted successfully.');
    }
    
    /**
     * View RSVPs for an event
     */
    public function rsvps($id)
    {
        $event = $this->eventModel->find($id);
        
        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event not found.');
        }
        
        $rsvps = $this->rsvpModel->getRsvpsWithMembers($id);
        
        $data = [
            'pageTitle' => 'Event RSVPs - ' . $event['title'],
            'event' => $event,
            'rsvps' => $rsvps,
            'goingCount' => $this->rsvpModel->getCountByEvent($id, 'going'),
            'maybeCount' => $this->rsvpModel->getCountByEvent($id, 'maybe'),
            'declinedCount' => $this->rsvpModel->getCountByEvent($id, 'declined')
        ];
        
        return view('admin/events/rsvps', $data);
    }
    
    /**
     * Export event RSVPs to CSV
     */
    public function exportRsvps($id)
    {
        $event = $this->eventModel->find($id);
        
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found.');
        }
        
        $rsvps = $this->rsvpModel->getRsvpsWithMembers($id);
        
        $filename = 'rsvps_' . $event['slug'] . '_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, ['Name', 'Email', 'Phone', 'Status', 'RSVP Date']);
        
        // Data
        foreach ($rsvps as $rsvp) {
            fputcsv($output, [
                $rsvp['first_name'] . ' ' . $rsvp['last_name'],
                $rsvp['email'],
                $rsvp['phone'] ?? '-',
                ucfirst($rsvp['status']),
                date('Y-m-d H:i', strtotime($rsvp['created_at']))
            ]);
        }
        
        fclose($output);
        exit();
    }
}