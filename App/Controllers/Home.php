<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\EventModel;
use App\Models\NewsModel;
use App\Models\ExecutiveModel;
use App\Models\BannerModel;
use App\Models\AnnouncementModel;
use App\Libraries\BirthdayEngine;
use App\Models\ProfileReactionModel;

class Home extends BaseController
{
    protected $memberModel;
    protected $eventModel;
    protected $newsModel;
    protected $executiveModel;
    protected $bannerModel;
    protected $announcementModel;
    protected $birthdayEngine;
    
    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->eventModel = new EventModel();
        $this->newsModel = new NewsModel();
        $this->executiveModel = new ExecutiveModel();
        $this->bannerModel = new BannerModel();
        $this->announcementModel = new AnnouncementModel();
        $this->birthdayEngine = new BirthdayEngine();
    }
    
    public function index()
    {
        // Get today's birthdays
        $todaysBirthdays = $this->birthdayEngine->getTodaysBirthdays();
        
        // Get upcoming birthdays
        $upcomingBirthdays = $this->birthdayEngine->getUpcomingBirthdays();
        
        // Get progress tracker data
        $progressData = $this->birthdayEngine->getProgressData();
        
        // Get member spotlight (random approved member)
        $memberSpotlight = $this->memberModel->where('is_approved', 1)
                                              ->where('is_active', 1)
                                              ->orderBy('RAND()')
                                              ->first();
        
        // Get upcoming events
        $upcomingEvents = $this->eventModel->where('event_date >=', date('Y-m-d'))
                                           ->where('status', 'upcoming')
                                           ->orderBy('event_date', 'ASC')
                                           ->findAll(5);
        
        // Get featured events
        $featuredEvents = $this->eventModel->where('is_featured', 1)
                                           ->where('event_date >=', date('Y-m-d'))
                                           ->orderBy('event_date', 'ASC')
                                           ->findAll(3);
        
        // Get latest news
        $latestNews = $this->newsModel->where('is_published', 1)
                                      ->where('published_at <=', date('Y-m-d H:i:s'))
                                      ->orderBy('published_at', 'DESC')
                                      ->findAll(3);
        
        // Get leadership/executives
        $leadership = $this->executiveModel->select('executives.*, members.first_name, members.last_name, members.profile_photo, members.occupation')
                                           ->join('members', 'members.id = executives.member_id')
                                           ->orderBy('executives.sort_order', 'ASC')
                                           ->findAll();
        
        // Get active banners
        $banners = $this->bannerModel->where('is_active', 1)
                                     ->orderBy('sort_order', 'ASC')
                                     ->findAll();
        
        // Get active announcements
        $announcements = $this->announcementModel->where('is_active', 1)
                                                 ->where('expires_at >', date('Y-m-d H:i:s'))
                                                 ->orWhere('expires_at IS NULL')
                                                 ->findAll();
        
        // Check if should show confetti
        $showConfetti = false;
        if (session()->get('isLoggedIn')) {
            $showConfetti = $this->birthdayEngine->isMemberBirthday(session()->get('memberId'));
        }
        
        $data = [
            'pageTitle' => 'Welcome',
            'todaysBirthdays' => $todaysBirthdays,
            'upcomingBirthdays' => $upcomingBirthdays,
            'progressData' => $progressData,
            'memberSpotlight' => $memberSpotlight,
            'upcomingEvents' => $upcomingEvents,
            'featuredEvents' => $featuredEvents,
            'latestNews' => $latestNews,
            'leadership' => $leadership,
            'banners' => $banners,
            'announcements' => $announcements,
            'showConfetti' => $showConfetti,
            'birthMonth' => get_birth_month(),
            'associationName' => get_association_name(),
            'motto' => setting('motto', 'Unity Through Birth')
        ];
        
        return view('home/index', $data);
    }
}