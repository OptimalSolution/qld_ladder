<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function contact()
    {
        return view('frontend.contact');
    }
}
