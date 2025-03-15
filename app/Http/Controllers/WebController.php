<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class WebController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function arbitration()
    {
        return view('arbitration');
    }

    public function operation()
    {
        return view('operation');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
