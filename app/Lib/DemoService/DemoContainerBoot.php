<?php

namespace App\Lib\DemoService;

use App\Models\Photo;

class DemoContainerBoot
{
    public function getPhotos()
    {
        $ph = new Photo;
        $photos = $ph->limit(5)->get();
        $photoslist = '';
        foreach ($photos as $photo) {
            $photoslist .= '<img class="demo_img" src="'.$photo->file.'">&nbsp;';
        }
        return empty($photoslist) ? 'изображения не найдены' : $photoslist;
    }
}
