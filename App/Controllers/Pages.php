<?php

namespace App\Controllers;

use App\Models\PageModel;

class Pages extends BaseController
{
    protected $pageModel;
    
    public function __construct()
    {
        $this->pageModel = new PageModel();
    }
    
    /**
     * Display a page by slug
     */
    public function view($slug)
    {
        $page = $this->pageModel->where('slug', $slug)
                                ->where('is_published', 1)
                                ->first();
        
        if (!$page) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $data = [
            'pageTitle' => $page['title'],
            'page' => $page
        ];
        
        return view('pages/view', $data);
    }
    
    /**
     * About page
     */
    public function about()
    {
        return $this->view('about');
    }
    
    /**
     * Contact page (will be enhanced in Step 18)
     */
    public function contact()
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
}