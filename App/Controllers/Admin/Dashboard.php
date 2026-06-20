<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\EventModel;
use App\Models\NewsModel;
use App\Models\BirthdayWishModel;
use App\Models\MessageModel;
use App\Libraries\BirthdayEngine;

class Dashboard extends BaseController
{
    protected $memberModel;
    protected $eventModel;
    protected $newsModel;
    protected $wishModel;
    protected $messageModel;
    protected $birthdayEngine;
    
    public function __construct()
    {
        // Check if user is logged in and is admin
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->memberModel = new MemberModel();
        $this->eventModel = new EventModel();
        $this->newsModel = new NewsModel();
        $this->wishModel = new BirthdayWishModel();
        $this->messageModel = new MessageModel();
        $this->birthdayEngine = new BirthdayEngine();
    }
    
    public function index()
    {
        // Statistics
        $totalMembers = $this->memberModel->where('is_approved', 1)->countAllResults();
        $pendingMembers = $this->memberModel->where('is_approved', 0)->where('is_active', 1)->countAllResults();
        $birthMonthMembers = $this->birthdayEngine->getBirthdayCount();
        
        $upcomingEvents = $this->eventModel->where('event_date >=', date('Y-m-d'))
                                           ->where('status', 'upcoming')
                                           ->countAllResults();
        
        $totalEvents = $this->eventModel->countAllResults();
        
        $publishedNews = $this->newsModel->where('is_published', 1)->countAllResults();
        
        $pendingWishes = $this->wishModel->where('is_approved', 0)->countAllResults();
        
        $unreadMessages = $this->messageModel->where('is_read', 0)->countAllResults();
        
        // Recent members
        $recentMembers = $this->memberModel->orderBy('created_at', 'DESC')->findAll(5);
        
        // Recent events
        $recentEvents = $this->eventModel->orderBy('created_at', 'DESC')->findAll(5);
        
        // Recent messages
        $recentMessages = $this->messageModel->orderBy('created_at', 'DESC')->findAll(5);
        
        // Chart data - Member growth by month (last 6 months)
        $memberGrowth = $this->getMemberGrowthData();
        
        // Chart data - Members by county (top 5)
        $countyData = $this->getCountyDistributionData();
        
        // Chart data - Members by gender
        $genderData = $this->getGenderDistributionData();
        
        $data = [
            'pageTitle' => 'Admin Dashboard',
            'totalMembers' => $totalMembers,
            'pendingMembers' => $pendingMembers,
            'birthMonthMembers' => $birthMonthMembers,
            'upcomingEvents' => $upcomingEvents,
            'totalEvents' => $totalEvents,
            'publishedNews' => $publishedNews,
            'pendingWishes' => $pendingWishes,
            'unreadMessages' => $unreadMessages,
            'recentMembers' => $recentMembers,
            'recentEvents' => $recentEvents,
            'recentMessages' => $recentMessages,
            'memberGrowth' => $memberGrowth,
            'countyData' => $countyData,
            'genderData' => $genderData,
            'birthMonth' => get_birth_month(),
            'associationName' => get_association_name()
        ];
        
        return view('admin/dashboard/index', $data);
    }
    
    private function getMemberGrowthData()
    {
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthStart = $month . '-01';
            $monthEnd = date('Y-m-t', strtotime($monthStart));
            
            $count = $this->memberModel->where('created_at >=', $monthStart)
                                       ->where('created_at <=', $monthEnd . ' 23:59:59')
                                       ->countAllResults();
            
            $data['months'][] = date('M Y', strtotime($monthStart));
            $data['counts'][] = $count;
        }
        
        return $data;
    }
    
    private function getCountyDistributionData()
    {
        $counties = $this->memberModel->select('county, COUNT(*) as count')
                                      ->where('county !=', '')
                                      ->where('county IS NOT NULL')
                                      ->where('is_approved', 1)
                                      ->groupBy('county')
                                      ->orderBy('count', 'DESC')
                                      ->limit(5)
                                      ->find();
        
        return [
            'labels' => array_column($counties, 'county'),
            'counts' => array_column($counties, 'count')
        ];
    }
    
    private function getGenderDistributionData()
    {
        $genders = $this->memberModel->select('gender, COUNT(*) as count')
                                     ->where('gender !=', '')
                                     ->where('gender IS NOT NULL')
                                     ->where('is_approved', 1)
                                     ->groupBy('gender')
                                     ->find();
        
        return [
            'labels' => array_column($genders, 'gender'),
            'counts' => array_column($genders, 'count')
        ];
    }
}