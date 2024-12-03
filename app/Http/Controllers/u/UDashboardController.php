<?php

namespace App\Http\Controllers\u;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UDashboardController extends Controller
{
    public function index()
    {
        $d['title']='Dashboard';
        return view('u.pages.dashboard',$d);
    }

}
