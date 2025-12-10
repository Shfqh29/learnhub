<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageAuthenticationController extends Controller
{
    public function index()
    {
        return view('module1.index'); // Akan cari resources/views/module1/index.blade.php
    }
}
