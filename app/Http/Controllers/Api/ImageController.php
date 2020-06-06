<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function show(Request $request, Image $image)
    {
        return response()->file($image->path());
    }

    public function explore(Request $request)
    {
        return Image::ofClassroom()->select('id', 'name')->get();
    }
}
