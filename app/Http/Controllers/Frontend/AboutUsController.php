<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function ShowAboutUs(){

        return view('frontend.about.about_us');
    }
}
