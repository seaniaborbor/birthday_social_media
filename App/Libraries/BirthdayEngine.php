<?php

namespace App\Libraries;

use App\Models\MemberModel;
use App\Models\BirthdayWishModel;

class BirthdayEngine
{
    protected $memberModel;
    protected $wishModel;
    protected $birthMonth;
    protected $birthMonthNumber;
    
    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->wishModel = new BirthdayWishModel();
        $this->birthMonth = get_birth_month();
        $this->birthMonthNumber = get_birth_month_number();
    }
    
    /**
     * Get today's birthdays for the configured birth month
     * 
     * @return array
     */
    public function getTodaysBirthdays()
    {
        $today = date('j');
        $currentMonth = date('n');
        
        $builder = $this->memberModel->where('birth_day', $today);
        
        // If current month is the configured birth month, show birthdays
        if ($currentMonth == $this->birthMonthNumber) {
            $builder->where('birth_month', $this->birthMonthNumber);
        } else {
            // For non-birth months, only show if admin override or preview
            $builder->where('birth_month', $this->birthMonthNumber);
        }
        
        return $builder->where('is_active', 1)
                       ->where('is_approved', 1)
                       ->findAll();
    }
    
    /**
     * Get upcoming birthdays (next 30 days) for the configured birth month
     * 
     * @return array
     */
    public function getUpcomingBirthdays()
    {
        $today = (int) date('j');
        $currentMonth = (int) date('n');
        
        $members = $this->memberModel->where('birth_month', $this->birthMonthNumber)
                                     ->where('is_active', 1)
                                     ->where('is_approved', 1)
                                     ->findAll();
        
        $upcoming = [];
        foreach ($members as $member) {
            $birthDay = (int) $member['birth_day'];
            
            if ($currentMonth == $this->birthMonthNumber) {
                if ($birthDay > $today) {
                    $daysUntil = $birthDay - $today;
                    $upcoming[] = [
                        'member' => $member,
                        'days_until' => $daysUntil
                    ];
                }
            } else {
                // Count days until next birthday month
                $daysUntilMonth = $this->getDaysUntilBirthMonth();
                $upcoming[] = [
                    'member' => $member,
                    'days_until' => $daysUntilMonth + $birthDay
                ];
            }
        }
        
        // Sort by days until
        usort($upcoming, function($a, $b) {
            return $a['days_until'] <=> $b['days_until'];
        });
        
        return array_slice($upcoming, 0, 10);
    }
    
    /**
     * Get days until the configured birth month
     * 
     * @return int
     */
    public function getDaysUntilBirthMonth()
    {
        $currentMonth = (int) date('n');
        $currentDay = (int) date('j');
        $currentYear = (int) date('Y');
        
        if ($currentMonth < $this->birthMonthNumber) {
            $targetDate = mktime(0, 0, 0, $this->birthMonthNumber, 1, $currentYear);
            $daysUntil = ceil(($targetDate - time()) / (60 * 60 * 24));
        } elseif ($currentMonth > $this->birthMonthNumber) {
            $targetDate = mktime(0, 0, 0, $this->birthMonthNumber, 1, $currentYear + 1);
            $daysUntil = ceil(($targetDate - time()) / (60 * 60 * 24));
        } else {
            // Current month is birth month
            $targetDate = mktime(0, 0, 0, $this->birthMonthNumber, $currentDay, $currentYear);
            if ($targetDate < time()) {
                $targetDate = mktime(0, 0, 0, $this->birthMonthNumber, $currentDay, $currentYear + 1);
            }
            $daysUntil = ceil(($targetDate - time()) / (60 * 60 * 24));
        }
        
        return $daysUntil;
    }
    
    /**
     * Check if today is a member's birthday
     * 
     * @param int $memberId
     * @return bool
     */
    public function isMemberBirthday($memberId)
    {
        $member = $this->memberModel->find($memberId);
        
        if (!$member) {
            return false;
        }
        
        $today = date('j');
        $currentMonth = date('n');
        
        return ($member['birth_day'] == $today && 
                $member['birth_month'] == $this->birthMonthNumber &&
                $currentMonth == $this->birthMonthNumber);
    }
    
    /**
     * Get all members with birthdays in the configured month
     * 
     * @return array
     */
    public function getAllMonthBirthdays()
    {
        return $this->memberModel->where('birth_month', $this->birthMonthNumber)
                                 ->where('is_active', 1)
                                 ->where('is_approved', 1)
                                 ->orderBy('birth_day', 'ASC')
                                 ->findAll();
    }
    
    /**
     * Get birthdays by day range
     * 
     * @param int $startDay
     * @param int $endDay
     * @return array
     */
    public function getBirthdaysByDayRange($startDay, $endDay)
    {
        return $this->memberModel->where('birth_month', $this->birthMonthNumber)
                                 ->where('birth_day >=', $startDay)
                                 ->where('birth_day <=', $endDay)
                                 ->where('is_active', 1)
                                 ->where('is_approved', 1)
                                 ->orderBy('birth_day', 'ASC')
                                 ->findAll();
    }
    
    /**
     * Get birthday count for the configured month
     * 
     * @return int
     */
    public function getBirthdayCount()
    {
        return $this->memberModel->where('birth_month', $this->birthMonthNumber)
                                 ->where('is_active', 1)
                                 ->where('is_approved', 1)
                                 ->countAllResults();
    }
    
    /**
     * Validate if birth month matches configured month
     * 
     * @param int $birthMonth
     * @param bool $adminOverride
     * @return bool
     */
    public function validateBirthMonth($birthMonth, $adminOverride = false)
    {
        if ($adminOverride) {
            return true;
        }
        
        return $birthMonth == $this->birthMonthNumber;
    }
    
    /**
     * Get progress tracker data for homepage
     * 
     * @return array
     */
    public function getProgressData()
    {
        $totalMembers = $this->memberModel->where('is_approved', 1)->countAllResults();
        $birthMonthMembers = $this->getBirthdayCount();
        
        $target = setting('membership_target', 1000);
        
        return [
            'total_members' => $totalMembers,
            'birth_month_members' => $birthMonthMembers,
            'target' => $target,
            'percentage' => $target > 0 ? min(100, round(($totalMembers / $target) * 100)) : 0,
            'days_until_birth_month' => $this->getDaysUntilBirthMonth(),
            'is_birth_month' => date('n') == $this->birthMonthNumber
        ];
    }
    
    /**
     * Send birthday notification (to be used by cron)
     * 
     * @return int Number of notifications sent
     */
    public function sendDailyBirthdayNotifications()
    {
        $todaysBirthdays = $this->getTodaysBirthdays();
        $emailService = \Config\Services::email();
        $sent = 0;
        
        $smtpConfigured = setting('smtp_host') && setting('smtp_user') && setting('smtp_pass');
        
        if (!$smtpConfigured) {
            return 0;
        }
        
        foreach ($todaysBirthdays as $member) {
            $emailService->setFrom(setting('smtp_user', 'noreply@bmams.com'), get_association_name());
            $emailService->setTo($member['email']);
            $emailService->setSubject('Happy Birthday from ' . get_association_name() . '!');
            
            $message = "
                <html>
                <body style='font-family: Merriweather, serif;'>
                    <h2>Happy Birthday, {$member['first_name']} {$member['last_name']}!</h2>
                    <p>On behalf of the entire " . get_association_name() . ", we wish you a wonderful birthday!</p>
                    <p>May your day be filled with joy and celebration.</p>
                    <p>Warm regards,<br>" . get_association_name() . "</p>
                </body>
                </html>
            ";
            
            $emailService->setMessage($message);
            
            if ($emailService->send()) {
                $sent++;
            }
            
            $emailService->clear();
        }
        
        return $sent;
    }
    
    /**
     * Get birthday confetti trigger status
     * 
     * @param int $memberId
     * @return bool
     */
    public function shouldShowConfetti($memberId = null)
    {
        if ($memberId) {
            return $this->isMemberBirthday($memberId);
        }
        
        // Check if any member has birthday today
        return count($this->getTodaysBirthdays()) > 0;
    }
}