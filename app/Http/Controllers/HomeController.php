<?php

namespace App\Http\Controllers;

use App\Models\Layanan;

class HomeController extends Controller
{
    public function index()
    {
        $layanans = Layanan::latest()->take(3)->get();

        return view('home', compact('layanans'));
    }
}