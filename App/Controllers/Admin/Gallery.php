<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GalleryModel;
use App\Models\GalleryPhotoModel;

class Gallery extends BaseController
{
    protected $galleryModel;
    protected $photoModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }
        
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
            'pageTitle' => 'Gallery Management',
            'albums' => $albums,
            'totalAlbums' => count($albums)
        ];
        
        return view('admin/gallery/index', $data);
    }
    
    /**
     * Create new album
     */
    public function createAlbum()
    {
        $data = [
            'pageTitle' => 'Create Album'
        ];
        
        return view('admin/gallery/create_album', $data);
    }
    
    /**
     * Store new album
     */
    public function storeAlbum()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[500]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        // Generate slug
        $slug = $this->galleryModel->generateSlug($this->request->getPost('title'));
        
        $albumData = [
            'title' => $this->request->getPost('title'),
            'slug' => $slug,
            'description' => $this->request->getPost('description')
        ];
        
        // Handle cover image
        $file = $this->request->getFile('cover_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'cover_' . time() . '_' . $file->getRandomName();
            $file->move('uploads/gallery', $newName);
            $albumData['cover_image'] = 'uploads/gallery/' . $newName;
        }
        
        if ($this->galleryModel->insert($albumData)) {
            return redirect()->to('/admin/gallery')->with('success', 'Album created successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create album.');
    }
    
    /**
     * View album and manage photos
     */
    public function viewAlbum($id)
    {
        $album = $this->galleryModel->find($id);
        
        if (!$album) {
            return redirect()->to('/admin/gallery')->with('error', 'Album not found.');
        }
        
        $photos = $this->photoModel->where('gallery_id', $id)
                                   ->orderBy('sort_order', 'ASC')
                                   ->findAll();
        
        $data = [
            'pageTitle' => $album['title'] . ' - Gallery',
            'album' => $album,
            'photos' => $photos
        ];
        
        return view('admin/gallery/view_album', $data);
    }
    
    /**
     * Edit album
     */
    public function editAlbum($id)
    {
        $album = $this->galleryModel->find($id);
        
        if (!$album) {
            return redirect()->to('/admin/gallery')->with('error', 'Album not found.');
        }
        
        $data = [
            'pageTitle' => 'Edit Album',
            'album' => $album
        ];
        
        return view('admin/gallery/edit_album', $data);
    }
    
    /**
     * Update album
     */
    public function updateAlbum($id)
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[500]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please fix the errors below.');
        }
        
        $albumData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description')
        ];
        
        // Update slug if title changed
        $album = $this->galleryModel->find($id);
        if ($album['title'] !== $this->request->getPost('title')) {
            $albumData['slug'] = $this->galleryModel->generateSlug($this->request->getPost('title'));
        }
        
        // Handle cover image
        $file = $this->request->getFile('cover_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old cover
            if ($album['cover_image'] && file_exists($album['cover_image'])) {
                unlink($album['cover_image']);
            }
            
            $newName = 'cover_' . time() . '_' . $file->getRandomName();
            $file->move('uploads/gallery', $newName);
            $albumData['cover_image'] = 'uploads/gallery/' . $newName;
        }
        
        if ($this->galleryModel->update($id, $albumData)) {
            return redirect()->to('/admin/gallery')->with('success', 'Album updated successfully.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update album.');
    }
    
    /**
     * Delete album
     */
    public function deleteAlbum($id)
    {
        $album = $this->galleryModel->find($id);
        
        if (!$album) {
            return redirect()->back()->with('error', 'Album not found.');
        }
        
        // Delete all photos in album
        $photos = $this->photoModel->where('gallery_id', $id)->findAll();
        foreach ($photos as $photo) {
            if ($photo['filename'] && file_exists($photo['filename'])) {
                unlink($photo['filename']);
            }
            $this->photoModel->delete($photo['id']);
        }
        
        // Delete cover image
        if ($album['cover_image'] && file_exists($album['cover_image'])) {
            unlink($album['cover_image']);
        }
        
        $this->galleryModel->delete($id);
        
        return redirect()->to('/admin/gallery')->with('success', 'Album deleted successfully.');
    }
    
    /**
     * Upload photo to album
     */
    public function uploadPhoto($albumId)
    {
        $album = $this->galleryModel->find($albumId);
        
        if (!$album) {
            return redirect()->back()->with('error', 'Album not found.');
        }
        
        $rules = [
            'photo' => 'uploaded[photo]|max_size[photo,10240]|is_image[photo]',
            'caption' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Gallery photo upload validation failed: {errors}', [
                'errors' => json_encode($this->validator->getErrors())
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }
        
        $file = $this->request->getFile('photo');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'photo_' . time() . '_' . $file->getRandomName();

            try {
                $file->move('uploads/gallery', $newName);
            } catch (\Throwable $exception) {
                log_message('error', 'Gallery photo move failed for album {album_id}: {message}', [
                    'album_id' => $albumId,
                    'message' => $exception->getMessage(),
                ]);

                return redirect()->back()->withInput()->with('error', 'Failed to move uploaded photo. Please try again.');
            }
            
            // Get max sort order
            $maxSort = $this->photoModel->where('gallery_id', $albumId)
                                        ->selectMax('sort_order')
                                        ->first();
            $sortOrder = ($maxSort['sort_order'] ?? 0) + 1;
            
            $photoData = [
                'gallery_id' => $albumId,
                'filename' => 'uploads/gallery/' . $newName,
                'caption' => $this->request->getPost('caption'),
                'sort_order' => $sortOrder
            ];
            
            if ($this->photoModel->insert($photoData)) {
                return redirect()->back()->with('success', 'Photo uploaded successfully.');
            }

            log_message('error', 'Gallery photo insert failed for album {album_id}', ['album_id' => $albumId]);
        }
        
        if ($file) {
            log_message('error', 'Gallery photo upload rejected for album {album_id}: {error}', [
                'album_id' => $albumId,
                'error' => $file->getErrorString(),
            ]);
        }
        
        return redirect()->back()->with('error', 'Failed to upload photo.');
    }
    
    /**
     * Delete photo
     */
    public function deletePhoto($photoId)
    {
        $photo = $this->photoModel->find($photoId);
        
        if (!$photo) {
            return redirect()->back()->with('error', 'Photo not found.');
        }
        
        // Delete file
        if ($photo['filename'] && file_exists($photo['filename'])) {
            unlink($photo['filename']);
        }
        
        $this->photoModel->delete($photoId);
        
        return redirect()->back()->with('success', 'Photo deleted successfully.');
    }
    
    /**
     * Reorder photos
     */
    public function reorderPhotos()
    {
        if ($this->request->getMethod() === 'POST') {
            $order = $this->request->getPost('order');
            
            if (!empty($order) && is_array($order)) {
                foreach ($order as $index => $id) {
                    $this->photoModel->update($id, ['sort_order' => $index + 1]);
                }
                return redirect()->back()->with('success', 'Photo order updated successfully.');
            }
        }
        
        return redirect()->back()->with('error', 'Failed to update order.');
    }
}
