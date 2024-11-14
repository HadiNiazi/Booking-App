<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class HomeController extends Controller
{
    public function openHomePage()
    {
        $events = Event::with(['category'])->get();

        return view('site.index', compact('events'));
    }

    public function openEventDetailsPage()
    {
        return view('site.details');
    }

    public function openThankuPage()
    {
        return view('site.thanku');
    }

    public function openCancelPage()
    {
        return view('site.cancel');
    }


}
