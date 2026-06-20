<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\EventModel;
use App\Models\EventRsvpModel;
use App\Libraries\BirthdayEngine;

class Reports extends BaseController
{
    protected $memberModel;
    protected $eventModel;
    protected $rsvpModel;
    protected $birthdayEngine;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->memberModel = new MemberModel();
        $this->eventModel = new EventModel();
        $this->rsvpModel = new EventRsvpModel();
        $this->birthdayEngine = new BirthdayEngine();
    }
    
    /**
     * Reports dashboard
     */
    public function index()
    {
        $data = [
            'pageTitle' => 'Reports',
            'totalMembers' => $this->memberModel->where('is_approved', 1)->countAllResults(),
            'birthMonthMembers' => $this->birthdayEngine->getBirthdayCount(),
            'totalEvents' => $this->eventModel->countAllResults(),
            'totalRsvps' => $this->rsvpModel->countAllResults(),
            'recentMembers' => $this->memberModel->orderBy('created_at', 'DESC')->findAll(10)
        ];
        
        return view('admin/reports/index', $data);
    }
    
    /**
     * Member demographics report
     */
    public function demographics()
    {
        // County distribution
        $countyData = $this->memberModel->select('county, COUNT(*) as count')
                                        ->where('county !=', '')
                                        ->where('county IS NOT NULL')
                                        ->where('is_approved', 1)
                                        ->groupBy('county')
                                        ->orderBy('count', 'DESC')
                                        ->findAll();
        
        // Gender distribution
        $genderData = $this->memberModel->select('gender, COUNT(*) as count')
                                        ->where('gender !=', '')
                                        ->where('gender IS NOT NULL')
                                        ->where('is_approved', 1)
                                        ->groupBy('gender')
                                        ->findAll();
        
        // Age distribution
        $currentYear = date('Y');
        $ageGroups = [
            '18-25' => 0, '26-35' => 0, '36-45' => 0, '46-55' => 0, '56+' => 0
        ];
        
        $members = $this->memberModel->where('is_approved', 1)->findAll();
        foreach ($members as $member) {
            if (!empty($member['birth_year'])) {
                $age = $currentYear - $member['birth_year'];
                if ($age >= 18 && $age <= 25) $ageGroups['18-25']++;
                elseif ($age >= 26 && $age <= 35) $ageGroups['26-35']++;
                elseif ($age >= 36 && $age <= 45) $ageGroups['36-45']++;
                elseif ($age >= 46 && $age <= 55) $ageGroups['46-55']++;
                elseif ($age >= 56) $ageGroups['56+']++;
            }
        }
        
        // Occupation distribution
        $occupationData = $this->memberModel->select('occupation, COUNT(*) as count')
                                            ->where('occupation !=', '')
                                            ->where('occupation IS NOT NULL')
                                            ->where('is_approved', 1)
                                            ->groupBy('occupation')
                                            ->orderBy('count', 'DESC')
                                            ->limit(10)
                                            ->findAll();
        
        $data = [
            'pageTitle' => 'Member Demographics',
            'countyData' => $countyData,
            'genderData' => $genderData,
            'ageGroups' => $ageGroups,
            'occupationData' => $occupationData,
            'totalMembers' => count($members)
        ];
        
        return view('admin/reports/demographics', $data);
    }
    
    /**
     * Export member report
     */
    public function exportMembers()
    {
        $type = $this->request->getGet('type') ?? 'all';
        
        $builder = $this->memberModel->where('is_approved', 1);
        
        if ($type === 'birth_month') {
            $builder->where('birth_month', get_birth_month_number());
        }
        
        $members = $builder->findAll();
        
        $filename = 'members_report_' . $type . '_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Birth Date', 'County', 'District', 'Occupation', 'Gender', 'Joined Date']);
        
        foreach ($members as $member) {
            fputcsv($output, [
                $member['id'],
                $member['first_name'],
                $member['last_name'],
                $member['email'],
                $member['phone'] ?? '-',
                get_month_name($member['birth_month']) . ' ' . $member['birth_day'] . ', ' . $member['birth_year'],
                $member['county'] ?? '-',
                $member['district'] ?? '-',
                $member['occupation'] ?? '-',
                $member['gender'] ?? '-',
                date('Y-m-d', strtotime($member['created_at']))
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Event participation report
     */
    public function events()
    {
        $events = $this->eventModel->findAll();
        
        foreach ($events as &$event) {
            $event['rsvp_count'] = $this->rsvpModel->getCountByEvent($event['id']);
            $event['going_count'] = $this->rsvpModel->getCountByEvent($event['id'], 'going');
        }
        
        $data = [
            'pageTitle' => 'Event Participation Report',
            'events' => $events,
            'totalRsvps' => $this->rsvpModel->countAllResults()
        ];
        
        return view('admin/reports/events', $data);
    }
}