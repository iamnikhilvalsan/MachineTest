<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Auth;

class HomeController extends Controller
{
    public function index()
    {
        $data['PageMenu'] = 'Dashboard';
        $data['PageName'] = 'Dashboard';
        return view('dashboard.dashboard', compact('data'));
    }
}
