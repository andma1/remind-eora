<?php

namespace App\Http\Controllers\Api;

use App\Classroom;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Image;

class GenerateController extends Controller
{
    public function __invoke(Request $request, Classroom $classroom)
    {
        $request->validate([
            'background' => 'required|string'
        ]);

        $script = app_path('AI/script.py');
        $fileName = $classroom->generateUniqueString($classroom->name) . '-result.png';
        file_put_contents(public_path('files' . DIRECTORY_SEPARATOR . $classroom->dir . DIRECTORY_SEPARATOR . 'backgorund.png'), base64_decode($request->get('background')));

        $result = shell_exec("python3 {$script} -d {$classroom->dir} -p {$fileName}");

        $image = new Image([
            'owner_type' => Classroom::class,
            'owner_id' => $classroom->id,
            'name' => $fileName
        ]);


        dd(public_path('results' . DIRECTORY_SEPARATOR . 'avatar-0.731759001591474227' . DIRECTORY_SEPARATOR . 'avatar-0.139357001591487642-result.png'), $image->path());
        dd(
            file_exists(
                public_path('results/avatar-0.731759001591474227/avatar-0.139357001591487642-result.png')
            )
        );

        dd(
            file_get_contents(public_path('results/avatar-0.731759001591474227/avatar-0.139357001591487642-result.png'))
        );
        abort_if(!file_exists($image->path()), 400, "Failed to generate image");

        $image->save();

        return response()->file($image->path());
    }
}
