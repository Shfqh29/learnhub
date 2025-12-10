<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageAssessmentController extends Controller
{
    public function index()
    {
        return view('module4.index'); // Akan cari resources/views/module4/index.blade.php
    }
}
