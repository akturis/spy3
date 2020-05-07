<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
//
    public function index()
    {
        return view('list');
    }
    public function stats()
    {
        return view('graph');
    }
    public function top()
    {
        return view('list');
    }
}
