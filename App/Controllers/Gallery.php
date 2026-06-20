<?php

namespace App\Controllers;

use App\Models\GalleryModel;
use App\Models\GalleryPhotoModel;

class Gallery extends BaseController
{
    protected $galleryModel;
    protected $photoModel;
    
    public function __construct()
    {
        $this->galleryModel = new GalleryModel();
        $this->photoModel = new GalleryPhotoModel();
    }
    
    /**
     * List all albums
     */
    public function index()
    {
        $albums = $this->galleryModel->orderBy('created_at', 'DESC')->findAll();
        
        // Get photo count for each album
        foreach ($albums as &$album) {
            $album['photo_count'] = $this->photoModel->where('gallery_id', $album['id'])->countAllResults();
        }
        
        $data = [
            'pageTitle' => 'Photo Gallery',
            'albums' => $albums
        ];
        
        return view('gallery/index', $data);
    }
    
    /**
     * View album
     */
    public function viewAlbum($slug)
    {
        $album = $this->galleryModel->where('slug', $slug)->first();
        
        if (!$album) {
            return redirect()->to('/gallery')->with('error', 'Album not found.');
        }
        
        $photos = $this->photoModel->where('gallery_id', $album['id'])
                                   ->orderBy('sort_order', 'ASC')
                                   ->findAll();
        
        $data = [
            'pageTitle' => $album['title'],
            'album' => $album,
            'photos' => $photos
        ];
        
        return view('gallery/view_album', $data);
    }
}