<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function rules()
    {
        return view('pages.rules');
    }

    public function store()
    {
        return view('store.index');
    }
}
