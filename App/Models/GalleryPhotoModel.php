<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryPhotoModel extends Model
{
    protected $table = 'gallery_photos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['gallery_id', 'filename', 'caption', 'sort_order'];
    protected $useTimestamps = false;
}
