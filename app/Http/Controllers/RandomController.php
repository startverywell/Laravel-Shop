<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RandomController extends Controller
{
    public function index(Request $request)
    {
        return Str::random(20);
    }
}
