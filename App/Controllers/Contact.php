<?php

namespace App\Controllers;

use App\Models\MessageModel;

class Contact extends BaseController
{
    protected $messageModel;
    
    public function __construct()
    {
        $this->messageModel = new MessageModel();
    }
    
    /**
     * Display contact page
     */
    public function index()
    {
        $data = [
            'pageTitle' => 'Contact Us',
            'associationName' => get_association_name(),
            'associationEmail' => setting('association_email'),
            'associationPhone' => setting('association_phone'),
            'associationAddress' => setting('association_address'),
            'mapEmbed' => setting('contact_map_embed'),
            'latitude' => setting('contact_latitude', '6.290743'),
            'longitude' => setting('contact_longitude', '-10.761421')
        ];
        
        return view('pages/contact', $data);
    }
    
    /**
     * Process contact form submission
     */
   /**
/**
 * Process contact form submission
 */
/**
 * Process contact form submission
 */
public function submit()
{
    $rules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email',
        'subject' => 'required|min_length[3]|max_length[255]',
        'message' => 'required|min_length[10]|max_length[5000]'
    ];
    
    if (!$this->validate($rules)) {
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors())
            ->with('error', 'Please fix the errors below.');
    }
    
    // Using Query Builder - completely SQL injection safe
    $db = \Config\Database::connect();
    $builder = $db->table('messages');
    
    $data = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'subject' => $this->request->getPost('subject'),
        'message' => $this->request->getPost('message'),
        'is_read' => 0,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    if ($builder->insert($data)) {
        // Send auto-reply to user (optional - add back if you have SMTP configured)
        // $this->sendAutoReply($data);
        
        return redirect()->back()->with('success', 'Your message has been sent. We will get back to you soon!');
    }
    
    return redirect()->back()->withInput()->with('error', 'Failed to send message. Please try again.');
}
    
    /**
     * Send auto-reply to user
     */
    private function sendAutoReply($messageData)
    {
        $smtpConfigured = setting('smtp_host') && setting('smtp_user') && setting('smtp_pass');
        
        if (!$smtpConfigured) {
            return false;
        }
        
        $email = \Config\Services::email();
        $email->setFrom(setting('smtp_user', 'noreply@bmams.com'), get_association_name());
        $email->setTo($messageData['email']);
        $email->setSubject('Thank you for contacting ' . get_association_name());
        
        $message = "
            <html>
            <body style='font-family: Merriweather, serif;'>
                <h2>Dear {$messageData['name']},</h2>
                <p>Thank you for reaching out to " . get_association_name() . ".</p>
                <p>We have received your message regarding: <strong>{$messageData['subject']}</strong></p>
                <p>Our team will review your inquiry and get back to you within 2-3 business days.</p>
                <p>Best regards,<br>" . get_association_name() . "</p>
                <hr>
                <p style='font-size: 11px;'>This is an automated response. Please do not reply to this email.</p>
            </body>
            </html>
        ";
        
        $email->setMessage($message);
        return $email->send();
    }
    
    /**
     * Notify admin about new message
     */
    private function notifyAdmin($messageData)
    {
        $smtpConfigured = setting('smtp_host') && setting('smtp_user') && setting('smtp_pass');
        $adminEmail = setting('association_email');
        
        if (!$smtpConfigured || !$adminEmail) {
            return false;
        }
        
        $email = \Config\Services::email();
        $email->setFrom(setting('smtp_user', 'noreply@bmams.com'), get_association_name());
        $email->setTo($adminEmail);
        $email->setSubject('New Contact Form Message: ' . $messageData['subject']);
        
        $message = "
            <html>
            <body style='font-family: Merriweather, serif;'>
                <h2>New Message from " . get_association_name() . " Website</h2>
                <p><strong>From:</strong> {$messageData['name']} ({$messageData['email']})</p>
                <p><strong>Subject:</strong> {$messageData['subject']}</p>
                <p><strong>Message:</strong></p>
                <p>{$messageData['message']}</p>
                <hr>
                <p><a href='" . base_url('/admin/messages') . "'>View in Admin Panel</a></p>
            </body>
            </html>
        ";
        
        $email->setMessage($message);
        return $email->send();
    }
}