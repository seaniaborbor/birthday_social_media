<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\BirthdayWishModel;
use App\Libraries\BirthdayEngine;

class Birthday extends BaseController
{
    protected $memberModel;
    protected $wishModel;
    protected $birthdayEngine;
    
    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->wishModel = new BirthdayWishModel();
        $this->birthdayEngine = new BirthdayEngine();
    }
    
    /**
     * Birthday Calendar View
     */
    public function calendar()
    {
        $year = $this->request->getGet('year') ?? date('Y');
        $month = $this->request->getGet('month') ?? date('n');
        
        // Get all birthdays for the configured month
        $birthdays = $this->birthdayEngine->getAllMonthBirthdays();
        
        // Group birthdays by day
        $birthdaysByDay = [];
        foreach ($birthdays as $birthday) {
            $day = (int)$birthday['birth_day'];
            if (!isset($birthdaysByDay[$day])) {
                $birthdaysByDay[$day] = [];
            }
            $birthdaysByDay[$day][] = $birthday;
        }
        
        // Get upcoming birthdays
        $upcomingBirthdays = $this->birthdayEngine->getUpcomingBirthdays();
        
        // Get birthday count
        $birthdayCount = $this->birthdayEngine->getBirthdayCount();
        
        // Get days until birth month
        $daysUntilBirthMonth = $this->birthdayEngine->getDaysUntilBirthMonth();
        
        // Check if we're in the birth month
        $isBirthMonth = (date('n') == get_birth_month_number());
        
        // Get current month's birthdays (for showing on calendar)
        $currentMonthBirthdays = [];
        if ($isBirthMonth) {
            $currentMonthBirthdays = $birthdaysByDay;
        }
        
        $data = [
            'pageTitle' => 'Birthday Calendar',
            'birthdaysByDay' => $birthdaysByDay,
            'upcomingBirthdays' => $upcomingBirthdays,
            'birthdayCount' => $birthdayCount,
            'daysUntilBirthMonth' => $daysUntilBirthMonth,
            'isBirthMonth' => $isBirthMonth,
            'currentMonthBirthdays' => $currentMonthBirthdays,
            'birthMonth' => get_birth_month(),
            'birthMonthNumber' => get_birth_month_number(),
            'currentYear' => $year,
            'currentMonth' => $month,
            'today' => date('j')
        ];
        
        return view('birthday/calendar', $data);
    }
    
    /**
     * Birthday Wall - Show approved wishes
     */
  /**
 * Birthday Wall - Show approved wishes
 */
public function wall()
{
    $wishes = $this->wishModel->getApprovedWishes(50);
    
    // Get today's birthday people for special highlighting
    $todaysBirthdays = $this->birthdayEngine->getTodaysBirthdays();
    $todayBirthdayIds = array_column($todaysBirthdays, 'id');
    
    // Get featured wish (random approved wish) including member info
    $featuredWish = $this->wishModel
                        ->select('birthday_wishes.*, members.first_name, members.last_name, members.profile_photo')
                        ->join('members', 'members.id = birthday_wishes.member_id')
                        ->where('birthday_wishes.is_approved', 1)
                        ->orderBy('RAND()')
                        ->first();
    
    $data = [
        'pageTitle' => 'Birthday Wall',
        'wishes' => $wishes,
        'featuredWish' => $featuredWish,
        'todayBirthdayIds' => $todayBirthdayIds,
        'birthMonth' => get_birth_month(),
        'isBirthMonth' => date('n') == get_birth_month_number(),
        'wishCount' => $this->wishModel->where('is_approved', 1)->countAllResults()
    ];
    
    return view('birthday/wall', $data);
}
    
    /**
     * Submit a birthday wish
     */
    public function submitWish()
    {
        if (!$this->request->getMethod() === 'POST') {
            return redirect()->back();
        }
        
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to submit a birthday wish.');
        }
        
        $rules = [
            'recipient_name' => 'required|min_length[2]|max_length[255]',
            'message' => 'required|min_length[5]|max_length[500]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        // Check if recipient is a member (optional)
        $recipientId = null;
        $recipientName = $this->request->getPost('recipient_name');
        
        $recipient = $this->memberModel->where('first_name', $recipientName)
                                        ->orWhere('CONCAT(first_name, " ", last_name)', $recipientName)
                                        ->first();
        
        if ($recipient) {
            $recipientId = $recipient['id'];
        }
        
        $wishData = [
            'member_id' => session()->get('memberId'),
            'recipient_id' => $recipientId,
            'recipient_name' => $recipientName,
            'message' => $this->request->getPost('message'),
            'is_approved' => 0  // Requires admin approval
        ];
        
        if ($this->wishModel->insert($wishData)) {
            return redirect()->back()->with('success', 'Your birthday wish has been submitted and is pending approval.');
        }
        
        return redirect()->back()->with('error', 'Failed to submit wish. Please try again.');
    }
    
    /**
     * Birthday API for calendar view (traditional POST)
     */
    public function getMonthData()
    {
        $month = $this->request->getPost('month') ?? date('n');
        $year = $this->request->getPost('year') ?? date('Y');
        
        $birthdays = $this->birthdayEngine->getAllMonthBirthdays();
        
        $birthdaysByDay = [];
        foreach ($birthdays as $birthday) {
            $day = (int)$birthday['birth_day'];
            if (!isset($birthdaysByDay[$day])) {
                $birthdaysByDay[$day] = [];
            }
            $birthdaysByDay[$day][] = $birthday;
        }
        
        return view('birthday/calendar_data', [
            'birthdaysByDay' => $birthdaysByDay,
            'currentMonth' => $month,
            'today' => date('j')
        ]);
    }
    
    /**
     * Birthday statistics
     */
    public function statistics()
    {
        $birthdays = $this->birthdayEngine->getAllMonthBirthdays();
        
        // Count by day
        $dayDistribution = [];
        foreach ($birthdays as $birthday) {
            $day = (int)$birthday['birth_day'];
            if (!isset($dayDistribution[$day])) {
                $dayDistribution[$day] = 0;
            }
            $dayDistribution[$day]++;
        }
        
        // Count by decade
        $decadeDistribution = [];
        foreach ($birthdays as $birthday) {
            if (!empty($birthday['birth_year'])) {
                $decade = floor($birthday['birth_year'] / 10) * 10;
                $decadeKey = $decade . 's';
                if (!isset($decadeDistribution[$decadeKey])) {
                    $decadeDistribution[$decadeKey] = 0;
                }
                $decadeDistribution[$decadeKey]++;
            }
        }
        
        // Count by county
        $countyDistribution = [];
        foreach ($birthdays as $birthday) {
            if (!empty($birthday['county'])) {
                if (!isset($countyDistribution[$birthday['county']])) {
                    $countyDistribution[$birthday['county']] = 0;
                }
                $countyDistribution[$birthday['county']]++;
            }
        }
        
        $data = [
            'pageTitle' => 'Birthday Statistics',
            'totalBirthdays' => count($birthdays),
            'dayDistribution' => $dayDistribution,
            'decadeDistribution' => $decadeDistribution,
            'countyDistribution' => $countyDistribution,
            'birthMonth' => get_birth_month(),
            'mostPopularDay' => !empty($dayDistribution) ? array_search(max($dayDistribution), $dayDistribution) : null,
            'mostPopularDecade' => !empty($decadeDistribution) ? array_search(max($decadeDistribution), $decadeDistribution) : null
        ];
        
        return view('birthday/statistics', $data);
    }
}