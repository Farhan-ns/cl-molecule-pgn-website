<?php

namespace App\Services;

class PublicPathService 
{
    private $path;

    public function __construct()
    {
        if (config('app.env') == 'local') {
            $this->path = public_path();
        } else {
            $this->path = base_path() . '/..';
        }
    }

    public function get() 
    {
        return $this->path;
    }

    public function getQrPath() 
    {
        return $this->get() . '/qr';
    }

    public function getQrPathUrl($id, $filename) 
    {
        return asset("/qr/$id/$filename");
    }
}