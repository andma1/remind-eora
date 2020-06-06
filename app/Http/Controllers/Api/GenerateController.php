<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GenerateController extends Controller
{
    public function __invoke()
    {
        return response()->json([], 200);
    }
}
