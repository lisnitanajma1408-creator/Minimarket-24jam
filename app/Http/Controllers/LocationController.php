<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;

class LocationController extends Controller
{
    public function index()
    {
        $store = StoreSetting::first();
        return view('lokasi', compact('store'));
    }
}