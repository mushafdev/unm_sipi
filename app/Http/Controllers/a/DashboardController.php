<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $d['title']='Dashboard';
        return view('a.pages.dashboard',$d);
    }

}
