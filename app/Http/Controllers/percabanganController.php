<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class percabanganController extends Controller
{
    public function index()
    {
        return view('tes.percabangan',['nilai' => 8]);
    }
}
