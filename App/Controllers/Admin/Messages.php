<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MessageModel;

class Messages extends BaseController
{
    protected $messageModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
        $this->messageModel = new MessageModel();
    }
    
    /**
     * List all messages
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;
        $status = $this->request->getGet('status');
        
        $builder = $this->messageModel;
        
        if ($status === 'unread') {
            $builder->where('is_read', 0);
        } elseif ($status === 'read') {
            $builder->where('is_read', 1);
        }
        
        $totalMessages = $builder->countAllResults(false);
        $messages = $builder->orderBy('created_at', 'DESC')
                            ->paginate($perPage, 'default', $page);
        
        $data = [
            'pageTitle' => 'Contact Messages',
            'messages' => $messages,
            'pager' => $this->messageModel->pager,
            'totalMessages' => $totalMessages,
            'unreadCount' => $this->messageModel->where('is_read', 0)->countAllResults(),
            'readCount' => $this->messageModel->where('is_read', 1)->countAllResults(),
            'status' => $status
        ];
        
        return view('admin/messages/index', $data);
    }
    
    /**
     * View single message
     */
    public function view($id)
    {
        $message = $this->messageModel->find($id);
        
        if (!$message) {
            return redirect()->to('/admin/messages')->with('error', 'Message not found.');
        }
        
        // Mark as read
        if (!$message['is_read']) {
            $this->messageModel->markAsRead($id);
            $message['is_read'] = 1;
        }
        
        $data = [
            'pageTitle' => 'View Message',
            'message' => $message
        ];
        
        return view('admin/messages/view', $data);
    }
    
    /**
     * Reply to message
     */
    public function reply($id)
    {
        $message = $this->messageModel->find($id);
        
        if (!$message) {
            return redirect()->back()->with('error', 'Message not found.');
        }
        
        $rules = [
            'reply_message' => 'required|min_length[10]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please enter a reply message.');
        }
        
        $replyMessage = $this->request->getPost('reply_message');
        
        if ($this->sendReplyEmail($message, $replyMessage)) {
            $this->messageModel->markAsReplied($id);
            return redirect()->to('/admin/messages')->with('success', 'Reply sent successfully.');
        }
        
        return redirect()->back()->with('error', 'Failed to send reply. Please check SMTP settings.');
    }
    
    /**
     * Delete message
     */
    public function delete($id)
    {
        $message = $this->messageModel->find($id);
        
        if (!$message) {
            return redirect()->back()->with('error', 'Message not found.');
        }
        
        $this->messageModel->delete($id);
        
        return redirect()->to('/admin/messages')->with('success', 'Message deleted successfully.');
    }
    
    /**
     * Bulk delete messages
     */
    public function bulkDelete()
    {
        $messageIds = $this->request->getPost('message_ids');
        
        if (empty($messageIds)) {
            return redirect()->back()->with('error', 'No messages selected.');
        }
        
        foreach ($messageIds as $id) {
            $this->messageModel->delete($id);
        }
        
        return redirect()->back()->with('success', count($messageIds) . ' messages deleted successfully.');
    }
    
    /**
     * Mark as read
     */
    public function markAsRead($id)
    {
        $message = $this->messageModel->find($id);
        
        if (!$message) {
            return redirect()->back()->with('error', 'Message not found.');
        }
        
        $this->messageModel->markAsRead($id);
        
        return redirect()->back()->with('success', 'Message marked as read.');
    }
    
    /**
     * Send reply email
     */
    private function sendReplyEmail($message, $replyMessage)
    {
        $smtpConfigured = setting('smtp_host') && setting('smtp_user') && setting('smtp_pass');
        
        if (!$smtpConfigured) {
            return false;
        }
        
        $email = \Config\Services::email();
        $email->setFrom(setting('smtp_user', 'noreply@bmams.com'), get_association_name());
        $email->setTo($message['email']);
        $email->setSubject('Re: ' . $message['subject']);
        
        $emailContent = "
            <html>
            <body style='font-family: Merriweather, serif;'>
                <h2>Dear {$message['name']},</h2>
                <p>Thank you for contacting " . get_association_name() . ". Here is our response to your inquiry:</p>
                
                <div style='background: #f0f0f0; padding: 15px; margin: 15px 0; border-left: 4px solid " . get_primary_color() . ";'>
                    <p><strong>Your message:</strong></p>
                    <p>{$message['message']}</p>
                </div>
                
                <div style='padding: 15px; margin: 15px 0;'>
                    <p><strong>Our response:</strong></p>
                    <p>{$replyMessage}</p>
                </div>
                
                <p>Best regards,<br>" . get_association_name() . "</p>
                <hr>
                <p style='font-size: 11px;'>This is an official response from " . get_association_name() . ".</p>
            </body>
            </html>
        ";
        
        $email->setMessage($emailContent);
        return $email->send();
    }
}