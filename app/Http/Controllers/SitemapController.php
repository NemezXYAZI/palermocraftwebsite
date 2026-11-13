<?php

namespace App\Http\Controllers;

use App\Models\News;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $pages = [
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('world'), 'priority' => '0.8'],
            ['loc' => route('guide'), 'priority' => '0.9'],
            ['loc' => route('news.index'), 'priority' => '0.7'],
            ['loc' => route('rules'), 'priority' => '0.6'],
            ['loc' => route('support'), 'priority' => '0.4'],
        ];

        $news = News::published()->latest('published_at')->get(['slug', 'published_at', 'updated_at']);

        return response()
            ->view('sitemap', ['pages' => $pages, 'news' => $news])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
