<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index()
    {
        return view('admin.disputes');
    }
}
