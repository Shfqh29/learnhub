<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageContentController extends Controller
{
    public function index()
    {
        return view('module3.index'); // point ke module3/index.blade.php
    }
}
