<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        return view('news.index', ['news' => News::published()->latest('published_at')->paginate(10)]);
    }

    public function show(string $slug)
    {
        return view('news.show', ['item' => News::published()->where('slug', $slug)->firstOrFail()]);
    }
}
