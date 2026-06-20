<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\UserRoleModel;
use App\Libraries\BirthdayEngine;

class Auth extends BaseController
{
    protected $memberModel;
    protected $userRoleModel;
    protected $birthdayEngine;
    protected $session;
    
    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->userRoleModel = new UserRoleModel();
        $this->birthdayEngine = new BirthdayEngine();
        $this->session = \Config\Services::session();
    }
    
    /**
     * Show login page
     */
    public function login()
    {
        // If already logged in, redirect to home
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        
        $data = [
            'pageTitle' => 'Login',
            'pageClass' => 'auth-page'
        ];
        
        return view('auth/login', $data);
    }
    
    /**
     * Process login form
     */
    public function doLogin()
    {
        // Validate form
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // Verify credentials
        $member = $this->memberModel->verifyCredentials($email, $password);
        
        if (!$member) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password, or your account is not approved yet.');
        }
        
        // Check if member is active and approved
        if (!$member['is_active'] || !$member['is_approved']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Your account is pending approval. Please check back later.');
        }
        
        // Update last login
        $this->memberModel->update($member['id'], [
            'last_login' => date('Y-m-d H:i:s')
        ]);
        
        // Set session data
        $this->session->set([
            'isLoggedIn' => true,
            'memberId' => $member['id'],
            'email' => $member['email'],
            'firstName' => $member['first_name'],
            'lastName' => $member['last_name'],
            'fullName' => $member['first_name'] . ' ' . $member['last_name'],
            'profilePhoto' => $member['profile_photo'],
            'birthDay' => $member['birth_day'],
            'birthMonth' => $member['birth_month'],
            'isBirthMonthMember' => is_birth_month_member($member['birth_month'])
        ]);
        
        // Check if user has admin role
        $isAdmin = $this->userRoleModel->hasRole($member['id'], 'super_admin') ||
                   $this->userRoleModel->hasRole($member['id'], 'admin');
        $this->session->set('isAdmin', $isAdmin);
        
        // Log the login action (will implement audit log in Step 19)
        log_message('info', 'User logged in: ' . $email);
        
        // Redirect based on role
        if ($isAdmin) {
            return redirect()->to('/admin/dashboard')->with('success', 'Welcome back, ' . $member['first_name'] . '!');
        }
        
        return redirect()->to('/')->with('success', 'Welcome back, ' . $member['first_name'] . '!');
    }
    
    /**
     * Show registration page
     */
    public function register()
    {
        // If already logged in, redirect to home
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        
        $data = [
            'pageTitle' => 'Register',
            'pageClass' => 'auth-page',
            'birthMonthConfigured' => get_birth_month(),
            'birthMonthNumber' => get_birth_month_number()
        ];
        
        return view('auth/register', $data);
    }
    
    /**
     * Process registration form
     */
    public function doRegister()
    {
        $birthMonthNumber = get_birth_month_number();
        $adminOverride = setting('admin_override', false);
        
        // Validation rules
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[members.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'birth_day' => 'required|numeric|greater_than[0]|less_than[32]',
            'birth_month' => 'required|numeric|greater_than[0]|less_than[13]',
            'birth_year' => 'required|numeric|greater_than[1900]|less_than[' . date('Y') . ']',
            'phone' => 'permit_empty|max_length[50]',
            'gender' => 'permit_empty|in_list[Male,Female,Other]',
            'county' => 'permit_empty|max_length[100]',
            'district' => 'permit_empty|max_length[100]',
            'occupation' => 'permit_empty|max_length[100]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $birthMonth = (int) $this->request->getPost('birth_month');
        
        // Validate birth month against configured month
        if (!$this->birthdayEngine->validateBirthMonth($birthMonth, $adminOverride)) {
            $errorMsg = "Registration is only open for individuals born in " . get_birth_month() . ". ";
            if ($adminOverride) {
                $errorMsg .= "Admin override is enabled, but your birth month doesn't match.";
            }
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMsg);
        }
        
        // Prepare member data
        $memberData = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'birth_day' => $this->request->getPost('birth_day'),
            'birth_month' => $birthMonth,
            'birth_year' => $this->request->getPost('birth_year'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'county' => $this->request->getPost('county'),
            'district' => $this->request->getPost('district'),
            'occupation' => $this->request->getPost('occupation'),
            'gender' => $this->request->getPost('gender'),
            'is_active' => 1,
            'is_approved' => setting('require_approval', true) ? 0 : 1
        ];
        
        // Insert member
        $insertId = $this->memberModel->insert($memberData);
        
        if (!$insertId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to register. Please try again.');
        }
        
        // Assign default 'member' role
        $roleModel = new \App\Models\RoleModel();
        $memberRole = $roleModel->where('name', 'member')->first();
        if ($memberRole) {
            $this->userRoleModel->assignRole($insertId, $memberRole['id']);
        }
        
        // Send notification email (if SMTP configured)
        $this->sendRegistrationNotification($memberData);
        
        $message = 'Registration successful! ';
        if (setting('require_approval', true)) {
            $message .= 'Your account is pending admin approval. You will be notified once approved.';
        } else {
            $message .= 'You can now login.';
        }
        
        return redirect()->to('/auth/login')->with('success', $message);
    }
    
    /**
     * Process logout
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'You have been logged out successfully.');
    }
    
    /**
     * Show forgot password page
     */
    public function forgotPassword()
    {
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        
        $data = [
            'pageTitle' => 'Forgot Password'
        ];
        
        return view('auth/forgot_password', $data);
    }
    
    /**
     * Process forgot password - send reset link
     */
    public function doForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please enter a valid email address.');
        }
        
        $email = $this->request->getPost('email');
        $member = $this->memberModel->findByEmail($email);
        
        if (!$member) {
            // Don't reveal if email exists or not for security
            return redirect()->back()->with('success', 'If your email is registered, you will receive a password reset link.');
        }
        
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token in database (we need to add reset_token and reset_expires columns)
        $this->memberModel->update($member['id'], [
            'reset_token' => $token,
            'reset_expires' => $expires
        ]);
        
        // Send reset email
        $this->sendPasswordResetEmail($member, $token);
        
        return redirect()->back()->with('success', 'Password reset link has been sent to your email.');
    }
    
    /**
     * Show reset password page
     */
    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to('/auth/login')->with('error', 'Invalid reset link.');
        }
        
        $member = $this->memberModel->where('reset_token', $token)
                                    ->where('reset_expires >', date('Y-m-d H:i:s'))
                                    ->first();
        
        if (!$member) {
            return redirect()->to('/auth/login')->with('error', 'Invalid or expired reset link. Please request a new one.');
        }
        
        $data = [
            'pageTitle' => 'Reset Password',
            'token' => $token,
            'email' => $member['email']
        ];
        
        return view('auth/reset_password', $data);
    }
    
    /**
     * Process password reset
     */
    public function doResetPassword()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }
        
        $token = $this->request->getPost('token');
        
        $member = $this->memberModel->where('reset_token', $token)
                                    ->where('reset_expires >', date('Y-m-d H:i:s'))
                                    ->first();
        
        if (!$member) {
            return redirect()->to('/auth/login')->with('error', 'Invalid or expired reset link.');
        }
        
        // Update password
        $this->memberModel->update($member['id'], [
            'password' => $this->request->getPost('password'),
            'reset_token' => null,
            'reset_expires' => null
        ]);
        
        return redirect()->to('/auth/login')->with('success', 'Password reset successful. Please login with your new password.');
    }
    
    /**
     * Send registration notification email
     */
    private function sendRegistrationNotification($memberData)
    {
        $smtpConfigured = setting('smtp_host') && setting('smtp_user') && setting('smtp_pass');
        
        if (!$smtpConfigured) {
            return false;
        }
        
        $email = \Config\Services::email();
        $email->setFrom(setting('smtp_user', 'noreply@bmams.com'), get_association_name());
        $email->setTo($memberData['email']);
        $email->setSubject('Welcome to ' . get_association_name());
        
        $message = "
            <html>
            <body style='font-family: Merriweather, serif;'>
                <h2>Welcome, {$memberData['first_name']}!</h2>
                <p>Thank you for registering with " . get_association_name() . ".</p>
                <p>Your registration has been received. " . (setting('require_approval', true) ? "You will be notified once your account is approved." : "You can now login to your account.") . "</p>
                <p>Best regards,<br>" . get_association_name() . "</p>
            </body>
            </html>
        ";
        
        $email->setMessage($message);
        return $email->send();
    }
    
    /**
     * Send password reset email
     */
    private function sendPasswordResetEmail($member, $token)
    {
        $smtpConfigured = setting('smtp_host') && setting('smtp_user') && setting('smtp_pass');
        
        if (!$smtpConfigured) {
            return false;
        }
        
        $resetLink = base_url('/auth/reset-password/' . $token);
        
        $email = \Config\Services::email();
        $email->setFrom(setting('smtp_user', 'noreply@bmams.com'), get_association_name());
        $email->setTo($member['email']);
        $email->setSubject('Password Reset Request - ' . get_association_name());
        
        $message = "
            <html>
            <body style='font-family: Merriweather, serif;'>
                <h2>Password Reset Request</h2>
                <p>You requested to reset your password. Click the link below to proceed:</p>
                <p><a href='{$resetLink}'>Reset Password</a></p>
                <p>This link expires in 1 hour.</p>
                <p>If you didn't request this, please ignore this email.</p>
                <p>Best regards,<br>" . get_association_name() . "</p>
            </body>
            </html>
        ";
        
        $email->setMessage($message);
        return $email->send();
    }
}