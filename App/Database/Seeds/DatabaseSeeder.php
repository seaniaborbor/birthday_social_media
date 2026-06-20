<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\MemberModel;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed roles
        $this->seedRoles();
        
        // Seed default admin user
        $this->seedAdminUser();
        
        // Seed default settings
        $this->seedSettings();
        
        // Seed default pages
        $this->seedPages();
        
        // Seed sample members
        $this->seedMembers();
        
        // Seed sample executives
        $this->seedExecutives();
        
        // Seed sample events
        $this->seedEvents();
        
        // Seed sample news
        $this->seedNews();
        
        // Seed sample banners
        $this->seedBanners();
        
        // Seed sample announcements
        $this->seedAnnouncements();
        
        // Seed sample birthday wishes
        $this->seedBirthdayWishes();
        
        // Seed sample messages
        $this->seedMessages();
    }
    
    private function seedRoles()
    {
        $roles = [
            ['name' => 'super_admin', 'description' => 'Full system access'],
            ['name' => 'admin', 'description' => 'Administrative access'],
            ['name' => 'editor', 'description' => 'Content management access'],
            ['name' => 'member', 'description' => 'Regular member access'],
        ];
        
        foreach ($roles as $role) {
            $this->db->table('roles')->insert($role);
        }
        
        // Permissions for super_admin
        $permissions = [
            'manage_settings', 'manage_members', 'manage_executives', 
            'manage_events', 'manage_news', 'manage_gallery', 
            'manage_wishes', 'manage_pages', 'manage_banners',
            'manage_announcements', 'view_messages', 'view_audit',
            'manage_roles', 'view_reports'
        ];
        
        foreach ($permissions as $permission) {
            $this->db->table('role_permissions')->insert([
                'role_id' => 1,
                'permission' => $permission
            ]);
        }
        
        // Permissions for admin
        $adminPermissions = [
            'manage_settings', 'manage_members', 'manage_executives', 
            'manage_events', 'manage_news', 'manage_gallery', 
            'manage_wishes', 'manage_pages', 'manage_banners',
            'manage_announcements', 'view_messages', 'view_reports'
        ];
        
        foreach ($adminPermissions as $permission) {
            $this->db->table('role_permissions')->insert([
                'role_id' => 2,
                'permission' => $permission
            ]);
        }
        
        // Permissions for editor
        $editorPermissions = [
            'manage_events', 'manage_news', 'manage_gallery', 
            'manage_wishes', 'manage_pages'
        ];
        
        foreach ($editorPermissions as $permission) {
            $this->db->table('role_permissions')->insert([
                'role_id' => 3,
                'permission' => $permission
            ]);
        }
    }
    
    private function seedAdminUser()
    {
        $memberModel = new MemberModel();
        
        $adminData = [
            'email' => 'admin@bmams.org',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'birth_day' => 1,
            'birth_month' => 1,
            'birth_year' => 1990,
            'phone' => '+231886123456',
            'address' => 'Sinkor, Monrovia',
            'city' => 'Monrovia',
            'county' => 'Montserrado',
            'district' => 'Sinkor',
            'occupation' => 'System Administrator',
            'gender' => 'Other',
            'profile_photo' => '',
            'is_active' => 1,
            'is_approved' => 1,
            'last_login' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $memberModel->insert($adminData);
        $adminId = $memberModel->getInsertID();
        
        // Assign super_admin role
        $this->db->table('user_roles')->insert([
            'user_id' => $adminId,
            'role_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    
    private function seedSettings()
    {
        $settings = [
            // General Settings
            [
                'key' => 'association_name', 
                'value' => 'September Born Association of Liberia', 
                'group' => 'general', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'birth_month', 
                'value' => 'September', 
                'group' => 'general', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'birth_month_number', 
                'value' => '9', 
                'group' => 'general', 
                'type' => 'number',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'motto', 
                'value' => 'Unity Through Birth, Strength Through Community', 
                'group' => 'general', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'vision', 
                'value' => 'A united Liberia where birth month associations drive sustainable development and social cohesion.', 
                'group' => 'general', 
                'type' => 'textarea',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'mission', 
                'value' => 'To unite individuals born in September for community development, mutual support, and national progress.', 
                'group' => 'general', 
                'type' => 'textarea',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'association_email', 
                'value' => 'info@septemberbornliberia.org', 
                'group' => 'general', 
                'type' => 'email',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'association_phone', 
                'value' => '+231886123456', 
                'group' => 'general', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'association_address', 
                'value' => 'Sinkor, Monrovia, Liberia', 
                'group' => 'general', 
                'type' => 'textarea',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'year_founded', 
                'value' => '2010', 
                'group' => 'general', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Branding Settings - Color theme
            [
                'key' => 'primary_color', 
                'value' => '#1a365d', 
                'group' => 'branding', 
                'type' => 'color',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'secondary_color', 
                'value' => '#c79a3d', 
                'group' => 'branding', 
                'type' => 'color',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'accent_color', 
                'value' => '#2d3748', 
                'group' => 'branding', 
                'type' => 'color',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'logo', 
                'value' => '', 
                'group' => 'branding', 
                'type' => 'image',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'favicon', 
                'value' => '', 
                'group' => 'branding', 
                'type' => 'image',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // SEO Settings
            [
                'key' => 'site_title', 
                'value' => 'September Born Association of Liberia', 
                'group' => 'seo', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'site_description', 
                'value' => 'The official association for Liberians born in September. Join us in celebrating our shared birth month and contributing to national development.', 
                'group' => 'seo', 
                'type' => 'textarea',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'site_keywords', 
                'value' => 'September born, Liberia, association, birth month, community, development', 
                'group' => 'seo', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Social Media
            [
                'key' => 'facebook_url', 
                'value' => 'https://facebook.com/septemberbornliberia', 
                'group' => 'social', 
                'type' => 'url',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'twitter_url', 
                'value' => 'https://twitter.com/septbornliberia', 
                'group' => 'social', 
                'type' => 'url',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'instagram_url', 
                'value' => 'https://instagram.com/septemberbornliberia', 
                'group' => 'social', 
                'type' => 'url',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'linkedin_url', 
                'value' => 'https://linkedin.com/company/septemberbornliberia', 
                'group' => 'social', 
                'type' => 'url',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'youtube_url', 
                'value' => 'https://youtube.com/@septemberbornliberia', 
                'group' => 'social', 
                'type' => 'url',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Security Settings
            [
                'key' => 'admin_override', 
                'value' => '0', 
                'group' => 'security', 
                'type' => 'checkbox',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'require_approval', 
                'value' => '1', 
                'group' => 'security', 
                'type' => 'checkbox',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Contact Settings
            [
                'key' => 'contact_latitude', 
                'value' => '6.290743', 
                'group' => 'contact', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'contact_longitude', 
                'value' => '-10.761421', 
                'group' => 'contact', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key' => 'working_hours', 
                'value' => 'Monday - Friday: 9:00 AM - 5:00 PM | Saturday: 10:00 AM - 2:00 PM', 
                'group' => 'contact', 
                'type' => 'text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        foreach ($settings as $setting) {
            $this->db->table('settings')->insert($setting);
        }
    }
    
    private function seedPages()
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h2>Our Mission</h2><p>To unite individuals born in September for community development and mutual support, fostering a sense of belonging and shared purpose.</p>
                <h2>Our Vision</h2><p>A united Liberia where birth month associations drive positive change, sustainable development, and social cohesion.</p>
                <h2>Our History</h2><p>Founded in 2010 by a group of passionate September-born Liberians, the September Born Association of Liberia has grown from a small gathering of friends to a thriving community of over 2,500 members across all 15 counties. Our journey began with a simple idea: that those born in the same month share a unique bond that can be harnessed for collective good.</p>
                <p>Over the years, we have organized numerous community outreach programs, educational scholarships, health initiatives, and cultural celebrations that have touched thousands of lives.</p>
                <h2>Core Values</h2><ul><li><strong>Unity:</strong> We stand together as one family</li><li><strong>Integrity:</strong> We uphold the highest standards</li><li><strong>Service:</strong> We give back to our communities</li><li><strong>Excellence:</strong> We strive for the best in all we do</li></ul>',
                'meta_title' => 'About Us - September Born Association of Liberia',
                'meta_description' => 'Learn about the September Born Association of Liberia - our mission, vision, history, and core values.',
                'is_published' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'content' => '<h2>Privacy Policy</h2><p>At September Born Association of Liberia, we value your privacy and are committed to protecting your personal information. This policy explains how we collect, use, and safeguard your data.</p>
                <h3>Information We Collect</h3><ul><li>Name and contact information</li><li>Birth date and location</li><li>Occupation and professional information</li><li>County and district of residence</li></ul>
                <h3>How We Use Your Information</h3><ul><li>Membership management</li><li>Event notifications and updates</li><li>Birthday celebrations and wishes</li><li>Association communications</li></ul>
                <p>We never share your personal information with third parties without your explicit consent.</p>',
                'meta_title' => 'Privacy Policy - September Born Association',
                'meta_description' => 'Read our privacy policy to understand how we protect your personal information.',
                'is_published' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms',
                'content' => '<h2>Terms of Service</h2><p>By joining the September Born Association of Liberia, you agree to abide by our constitution and bylaws. Membership is open to all Liberians born in the month of September.</p>
                <h3>Member Responsibilities</h3><ul><li>Pay annual dues as determined by the executive committee</li><li>Participate in association activities and events</li><li>Uphold the values and integrity of the association</li><li>Respect fellow members and the community</li></ul>
                <h3>Termination of Membership</h3><p>Membership may be terminated for violation of association bylaws, failure to pay dues, or behavior detrimental to the association.</p>',
                'meta_title' => 'Terms of Service - September Born Association',
                'meta_description' => 'Terms and conditions for membership in the September Born Association of Liberia.',
                'is_published' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        foreach ($pages as $page) {
            $this->db->table('pages')->insert($page);
        }
    }
    
    private function seedMembers()
    {
        $membersData = [
            [
                'first_name' => 'Joseph',
                'last_name' => 'Kerkula',
                'birth_day' => 15,
                'birth_month' => 9,
                'birth_year' => 1985,
                'email' => 'joseph.kerkula@gmail.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123457',
                'address' => 'Bushrod Island, Monrovia',
                'city' => 'Monrovia',
                'county' => 'Montserrado',
                'district' => 'Bushrod Island',
                'occupation' => 'Civil Engineer',
                'gender' => 'Male',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Mary',
                'last_name' => 'Johnson',
                'birth_day' => 23,
                'birth_month' => 9,
                'birth_year' => 1990,
                'email' => 'mary.johnson@yahoo.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123458',
                'address' => 'Sinkor, Monrovia',
                'city' => 'Monrovia',
                'county' => 'Montserrado',
                'district' => 'Sinkor',
                'occupation' => 'Secondary School Teacher',
                'gender' => 'Female',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Peter',
                'last_name' => 'Togba',
                'birth_day' => 8,
                'birth_month' => 9,
                'birth_year' => 1978,
                'email' => 'peter.togba@yahoo.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123459',
                'address' => 'Paynesville, Monrovia',
                'city' => 'Monrovia',
                'county' => 'Montserrado',
                'district' => 'Paynesville',
                'occupation' => 'Businessman',
                'gender' => 'Male',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-45 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Sheriff',
                'birth_day' => 12,
                'birth_month' => 9,
                'birth_year' => 1995,
                'email' => 'sarah.sheriff@gmail.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123460',
                'address' => 'Vai Town, Monrovia',
                'city' => 'Monrovia',
                'county' => 'Montserrado',
                'district' => 'Vai Town',
                'occupation' => 'Nurse',
                'gender' => 'Female',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Morris',
                'birth_day' => 4,
                'birth_month' => 9,
                'birth_year' => 1982,
                'email' => 'david.morris@gmail.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123461',
                'address' => 'Buchanan Street, Monrovia',
                'city' => 'Monrovia',
                'county' => 'Montserrado',
                'district' => 'Central Monrovia',
                'occupation' => 'Banker',
                'gender' => 'Male',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-60 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Elizabeth',
                'last_name' => 'Paye',
                'birth_day' => 18,
                'birth_month' => 9,
                'birth_year' => 1988,
                'email' => 'elizabeth.paye@yahoo.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123462',
                'address' => 'Kakata, Margibi County',
                'city' => 'Kakata',
                'county' => 'Margibi',
                'district' => 'Kakata District',
                'occupation' => 'Civil Servant',
                'gender' => 'Female',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-40 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Thomas',
                'last_name' => 'Williams',
                'birth_day' => 27,
                'birth_month' => 9,
                'birth_year' => 1975,
                'email' => 'thomas.williams@gmail.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123463',
                'address' => 'Gbarnga, Bong County',
                'city' => 'Gbarnga',
                'county' => 'Bong',
                'district' => 'Gbarnga District',
                'occupation' => 'Agricultural Extension Officer',
                'gender' => 'Male',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-90 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Grace',
                'last_name' => 'Brown',
                'birth_day' => 30,
                'birth_month' => 9,
                'birth_year' => 1992,
                'email' => 'grace.brown@yahoo.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123464',
                'address' => 'Harper, Maryland County',
                'city' => 'Harper',
                'county' => 'Maryland',
                'district' => 'Harper District',
                'occupation' => 'Pharmacist',
                'gender' => 'Female',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-50 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Sawyer',
                'birth_day' => 7,
                'birth_month' => 9,
                'birth_year' => 1980,
                'email' => 'john.sawyer@gmail.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123465',
                'address' => 'Tubmanburg, Bomi County',
                'city' => 'Tubmanburg',
                'county' => 'Bomi',
                'district' => 'Tubmanburg District',
                'occupation' => 'Mechanic',
                'gender' => 'Male',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-8 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-35 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Rebecca',
                'last_name' => 'Dolo',
                'birth_day' => 19,
                'birth_month' => 9,
                'birth_year' => 1996,
                'email' => 'rebecca.dolo@gmail.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123466',
                'address' => 'Ganta, Nimba County',
                'city' => 'Ganta',
                'county' => 'Nimba',
                'district' => 'Ganta District',
                'occupation' => 'University Student',
                'gender' => 'Female',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Horton',
                'birth_day' => 2,
                'birth_month' => 9,
                'birth_year' => 1970,
                'email' => 'michael.horton@yahoo.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123467',
                'address' => 'Buchanan, Grand Bassa County',
                'city' => 'Buchanan',
                'county' => 'Grand Bassa',
                'district' => 'Buchanan District',
                'occupation' => 'Retired Teacher',
                'gender' => 'Male',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-120 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Esther',
                'last_name' => 'Koroma',
                'birth_day' => 14,
                'birth_month' => 9,
                'birth_year' => 1991,
                'email' => 'esther.koroma@gmail.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123468',
                'address' => 'Logan Town, Monrovia',
                'city' => 'Monrovia',
                'county' => 'Montserrado',
                'district' => 'Logan Town',
                'occupation' => 'Accountant',
                'gender' => 'Female',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-28 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Samuel',
                'last_name' => 'Bishop',
                'birth_day' => 25,
                'birth_month' => 9,
                'birth_year' => 1987,
                'email' => 'samuel.bishop@gmail.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123469',
                'address' => 'Voinjama, Lofa County',
                'city' => 'Voinjama',
                'county' => 'Lofa',
                'district' => 'Voinjama District',
                'occupation' => 'NGO Program Officer',
                'gender' => 'Male',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-55 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Patricia',
                'last_name' => 'Jackson',
                'birth_day' => 9,
                'birth_month' => 9,
                'birth_year' => 1993,
                'email' => 'patricia.jackson@yahoo.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123470',
                'address' => 'New Kru Town, Monrovia',
                'city' => 'Monrovia',
                'county' => 'Montserrado',
                'district' => 'New Kru Town',
                'occupation' => 'Journalist',
                'gender' => 'Female',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => date('Y-m-d H:i:s', strtotime('-6 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Daniel',
                'last_name' => 'Cooper',
                'birth_day' => 21,
                'birth_month' => 9,
                'birth_year' => 1983,
                'email' => 'daniel.cooper@gmail.com',
                'password' => password_hash('member123', PASSWORD_DEFAULT),
                'phone' => '+231886123471',
                'address' => 'Saclepea, Nimba County',
                'city' => 'Saclepea',
                'county' => 'Nimba',
                'district' => 'Saclepea District',
                'occupation' => 'Police Officer',
                'gender' => 'Male',
                'profile_photo' => '',
                'is_active' => 1,
                'is_approved' => 1,
                'last_login' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-22 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        $memberModel = new MemberModel();
        
        foreach ($membersData as $memberData) {
            $memberModel->insert($memberData);
            $memberId = $memberModel->getInsertID();
            
            // Assign 'member' role
            $this->db->table('user_roles')->insert([
                'user_id' => $memberId,
                'role_id' => 4,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
    
    private function seedExecutives()
    {
        // Get members for executive positions
        $members = $this->db->table('members')
            ->whereIn('email', [
                'joseph.kerkula@gmail.com',
                'mary.johnson@yahoo.com',
                'david.morris@gmail.com',
                'elizabeth.paye@yahoo.com',
                'peter.togba@yahoo.com'
            ])
            ->get()
            ->getResult();
        
        $executiveMap = [];
        foreach ($members as $member) {
            $executiveMap[$member->email] = $member->id;
        }
        
        $executives = [
            [
                'member_id' => $executiveMap['joseph.kerkula@gmail.com'] ?? 2,
                'position' => 'President',
                'bio' => 'Joseph Kerkula is a Civil Engineer with over 15 years of experience. He has served the September Born Association with dedication since its founding. His vision is to expand the association\'s reach and impact across all 15 counties of Liberia.',
                'sort_order' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'member_id' => $executiveMap['mary.johnson@yahoo.com'] ?? 3,
                'position' => 'Vice President',
                'bio' => 'Mary Johnson is a dedicated educator who has been teaching secondary school for over 12 years. She brings strong leadership skills and a passion for youth development to the association.',
                'sort_order' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'member_id' => $executiveMap['david.morris@gmail.com'] ?? 5,
                'position' => 'Secretary General',
                'bio' => 'David Morris is a banker with expertise in financial management and organizational administration. He has been a member since 2012 and has served in various capacities within the association.',
                'sort_order' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'member_id' => $executiveMap['elizabeth.paye@yahoo.com'] ?? 6,
                'position' => 'Treasurer',
                'bio' => 'Elizabeth Paye is a civil servant with over 10 years of experience in government service. She is known for her integrity and financial prudence in managing the association\'s resources.',
                'sort_order' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'member_id' => $executiveMap['peter.togba@yahoo.com'] ?? 4,
                'position' => 'Public Relations Officer',
                'bio' => 'Peter Togba is a successful businessman with strong community ties. His expertise in public relations and networking has helped the association grow its membership significantly.',
                'sort_order' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        foreach ($executives as $executive) {
            $this->db->table('executives')->insert($executive);
        }
    }
    
    private function seedEvents()
    {
        $events = [
            [
                'title' => 'Annual General Meeting 2025',
                'slug' => 'annual-general-meeting-2025',
                'description' => 'Join us for our Annual General Meeting where we will present the annual report, elect new executive committee members, and discuss the strategic direction of the September Born Association of Liberia for the coming year.',
                'venue' => 'Monrovia City Hall, Monrovia, Liberia',
                'event_date' => date('Y-m-d', strtotime('+30 days')),
                'event_time' => '09:00:00',
                'featured_image' => '',
                'is_featured' => 1,
                'status' => 'upcoming',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'September Birthday Gala 2025',
                'slug' => 'september-birthday-gala-2025',
                'description' => 'A grand celebration of all September-born members! Enjoy an evening of fine dining, cultural performances, awards, and networking with fellow September-born Liberians. Dress code: Formal/Traditional.',
                'venue' => 'Royal Grand Hotel, Monrovia, Liberia',
                'event_date' => date('Y-m-d', strtotime('+60 days')),
                'event_time' => '17:00:00',
                'featured_image' => '',
                'is_featured' => 1,
                'status' => 'upcoming',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Community Health Outreach',
                'slug' => 'community-health-outreach',
                'description' => 'In partnership with the Ministry of Health, we are organizing a free health screening and awareness campaign in underserved communities. Services include blood pressure checks, malaria testing, HIV counseling, and health education.',
                'venue' => 'New Kru Town Community Center, Monrovia',
                'event_date' => date('Y-m-d', strtotime('+15 days')),
                'event_time' => '08:00:00',
                'featured_image' => '',
                'is_featured' => 1,
                'status' => 'upcoming',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Skills Development Workshop',
                'slug' => 'skills-development-workshop',
                'description' => 'A practical workshop focused on entrepreneurship and small business management. Participants will learn about business planning, marketing, financial management, and access to funding opportunities. Certificates will be provided.',
                'venue' => 'Monrovia Business Center, Sinkor',
                'event_date' => date('Y-m-d', strtotime('+45 days')),
                'event_time' => '09:00:00',
                'featured_image' => '',
                'is_featured' => 0,
                'status' => 'upcoming',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Sports and Cultural Festival 2025',
                'slug' => 'sports-cultural-festival-2025',
                'description' => 'A day of sports competitions (football, basketball, track events) and cultural performances celebrating Liberian heritage. All association members and their families are invited. Prizes and trophies will be awarded.',
                'venue' => 'Antoinette Tubman Stadium, Monrovia',
                'event_date' => date('Y-m-d', strtotime('+90 days')),
                'event_time' => '07:00:00',
                'featured_image' => '',
                'is_featured' => 0,
                'status' => 'upcoming',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        foreach ($events as $event) {
            $this->db->table('events')->insert($event);
        }
    }
    
    private function seedNews()
    {
        $news = [
            [
                'title' => 'September Born Association Launches New Digital Platform',
                'slug' => 'launches-new-digital-platform',
                'excerpt' => 'The September Born Association of Liberia has launched a modern digital platform to better serve its growing membership across the country.',
                'content' => '<p>Monrovia, Liberia — The September Born Association of Liberia (SBAL) has officially launched its new digital management system, a comprehensive platform designed to streamline membership management, event coordination, and community engagement.</p>
                <p>Speaking at the launch event, Association President Joseph Kerkula stated: "This platform represents a significant milestone in our journey to better serve our members. It will allow us to communicate more effectively, manage events efficiently, and provide better services to our growing community."</p>
                <p>The new platform features a member directory, event calendar, birthday wall, photo gallery, and news section. Members can now register online, update their profiles, and receive real-time notifications about association activities.</p>',
                'featured_image' => '',
                'category' => 'Announcement',
                'views' => 156,
                'is_published' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'September Born Association Donates to Local Schools',
                'slug' => 'donates-to-local-schools',
                'excerpt' => 'The association has donated learning materials and scholarships to underprivileged students in Montserrado County.',
                'content' => '<p>Monrovia, Liberia — In line with its commitment to community development, the September Born Association of Liberia has donated educational materials to three public schools in Montserrado County. The donation included textbooks, notebooks, writing materials, and furniture.</p>
                <p>The association also awarded scholarships to 15 outstanding students from financially disadvantaged backgrounds, covering tuition fees for the entire academic year.</p>
                <p>Association Treasurer Elizabeth Paye, who led the initiative, said: "Education is the foundation of our nation\'s future. As September-born individuals, we recognize our responsibility to invest in the next generation of Liberian leaders."</p>',
                'featured_image' => '',
                'category' => 'Community Service',
                'views' => 89,
                'is_published' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Record Attendance at September Gala 2024',
                'slug' => 'record-attendance-september-gala-2024',
                'excerpt' => 'The 2024 September Birthday Gala saw record attendance with over 250 members celebrating in style.',
                'content' => '<p>Monrovia, Liberia — The September Born Association of Liberia hosted its most successful birthday gala to date, with over 250 members and guests in attendance at the Royal Grand Hotel.</p>
                <p>The evening featured cultural performances, awards for outstanding members, and a special tribute to the founding members of the association. Guests enjoyed a sumptuous dinner and danced the night away to live music.</p>
                <p>Special awards were presented to members who have demonstrated exceptional service to the association and the community.</p>',
                'featured_image' => '',
                'category' => 'Events',
                'views' => 234,
                'is_published' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'September Born Association Joins National Development Efforts',
                'slug' => 'joins-national-development-efforts',
                'excerpt' => 'The association has pledged its support to national development initiatives in Liberia.',
                'content' => '<p>Monrovia, Liberia — The September Born Association of Liberia has officially joined the National Development Coalition, committing its resources and membership to support key national development initiatives across the country.</p>
                <p>The association has pledged to support initiatives in education, healthcare, youth empowerment, and economic development. This partnership with government and other civil society organizations is expected to amplify the impact of the association\'s community programs.</p>',
                'featured_image' => '',
                'category' => 'Partnerships',
                'views' => 67,
                'is_published' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime('-45 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-45 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'New Executive Committee Sworn In',
                'slug' => 'new-executive-committee-sworn-in',
                'excerpt' => 'The newly elected executive committee of the September Born Association has been officially sworn in to lead the association for the next term.',
                'content' => '<p>Monrovia, Liberia — A formal ceremony was held to swear in the newly elected executive committee of the September Born Association of Liberia. The event was attended by members, dignitaries, and representatives from partner organizations.</p>
                <p>The new committee includes Joseph Kerkula as President, Mary Johnson as Vice President, David Morris as Secretary General, Elizabeth Paye as Treasurer, and Peter Togba as Public Relations Officer.</p>
                <p>In his inaugural address, President Kerkula outlined his vision for the association: "We will focus on strengthening our membership base, expanding our community programs, and building strategic partnerships."</p>',
                'featured_image' => '',
                'category' => 'Leadership',
                'views' => 112,
                'is_published' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime('-60 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-60 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        foreach ($news as $item) {
            $this->db->table('news')->insert($item);
        }
    }
    
    private function seedBanners()
    {
        $banners = [
            [
                'title' => 'Welcome to the September Born Association of Liberia',
                'subtitle' => 'Unity Through Birth, Strength Through Community',
                'image' => 'banner_1.jpg',
                'button_text' => 'Join Now',
                'button_link' => '/register',
                'sort_order' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Celebrate Your September Birth with Us',
                'subtitle' => 'Join over 2,500 September-born Liberians in making a difference',
                'image' => 'banner_2.jpg',
                'button_text' => 'Learn More',
                'button_link' => '/about',
                'sort_order' => 2,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Upcoming Events',
                'subtitle' => 'Don\'t miss our Annual General Meeting and Birthday Gala',
                'image' => 'banner_3.jpg',
                'button_text' => 'View All Events',
                'button_link' => '/events',
                'sort_order' => 3,
                'is_active' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        foreach ($banners as $banner) {
            $this->db->table('banners')->insert($banner);
        }
    }
    
    private function seedAnnouncements()
    {
        $announcements = [
            [
                'message' => '🎉 Welcome to the September Born Association of Liberia! Please update your profile information and check out our upcoming events.',
                'type' => 'info',
                'is_dismissible' => 1,
                'is_active' => 1,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'message' => '📢 The Annual General Meeting has been scheduled for ' . date('F d, Y', strtotime('+30 days')) . '. All members are encouraged to attend.',
                'type' => 'warning',
                'is_dismissible' => 1,
                'is_active' => 1,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+35 days')),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'message' => '🎂 Happy Birthday to all our September-born members! May your month be filled with joy and blessings.',
                'type' => 'success',
                'is_dismissible' => 1,
                'is_active' => 1,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+3 days')),
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        foreach ($announcements as $announcement) {
            $this->db->table('announcements')->insert($announcement);
        }
    }
    
    private function seedBirthdayWishes()
    {
        // Get admin and some members
        $admin = $this->db->table('members')->where('email', 'admin@bmams.org')->get()->getRow();
        $members = $this->db->table('members')
            ->where('is_active', 1)
            ->where('is_approved', 1)
            ->limit(5)
            ->get()
            ->getResult();
        
        if ($admin && count($members) >= 2) {
            $wishes = [
                [
                    'member_id' => $admin->id,
                    'recipient_id' => $members[0]->id ?? null,
                    'recipient_name' => $members[0]->first_name . ' ' . $members[0]->last_name,
                    'message' => 'Happy Birthday! Wishing you a wonderful year ahead filled with success and happiness. Your contributions to the association are truly appreciated.',
                    'is_approved' => 1,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                ],
            ];
            
            if (isset($members[1])) {
                $wishes[] = [
                    'member_id' => $members[0]->id ?? null,
                    'recipient_id' => $members[1]->id ?? null,
                    'recipient_name' => $members[1]->first_name . ' ' . $members[1]->last_name,
                    'message' => 'Happy Birthday to a dedicated member of our association! May your day be as special as you are.',
                    'is_approved' => 1,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                ];
            }
            
            if (isset($members[2])) {
                $wishes[] = [
                    'member_id' => $members[1]->id ?? null,
                    'recipient_id' => $members[2]->id ?? null,
                    'recipient_name' => $members[2]->first_name . ' ' . $members[2]->last_name,
                    'message' => 'Warmest wishes on your birthday! Thank you for being such an active and valuable member of the September Born community.',
                    'is_approved' => 1,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                ];
            }
            
            foreach ($wishes as $wish) {
                $this->db->table('birthday_wishes')->insert($wish);
            }
        }
    }
    
    private function seedMessages()
    {
        $messages = [
            [
                'name' => 'John Kamara',
                'email' => 'john.kamara@gmail.com',
                'subject' => 'Membership Inquiry',
                'message' => 'Hello, I am interested in joining the September Born Association. I was born on September 15, 1985. Could you please guide me through the registration process?',
                'is_read' => 0,
                'replied_at' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'name' => 'Martha Wright',
                'email' => 'martha.wright@yahoo.com',
                'subject' => 'Event Volunteering',
                'message' => 'I would like to volunteer for the upcoming Community Health Outreach event. I am a qualified nurse with experience in health screening. Please let me know how I can help.',
                'is_read' => 0,
                'replied_at' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'name' => 'Robert Davis',
                'email' => 'robert.davis@gmail.com',
                'subject' => 'Partnership Proposal',
                'message' => 'I am reaching out on behalf of a local NGO that would like to partner with the September Born Association for community development projects. We are particularly interested in your education and health initiatives.',
                'is_read' => 1,
                'replied_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
        ];
        
        foreach ($messages as $message) {
            $this->db->table('messages')->insert($message);
        }
    }
}