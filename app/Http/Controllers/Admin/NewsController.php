<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    public function index()
    {
        return view('admin.news.index', ['news' => News::with('author')->latest()->paginate(20)]);
    }

    public function create()
    {
        return view('admin.news.form', ['item' => new News]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['author_id'] = $request->user()->id;
        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'Новину створено.');
    }

    public function edit(News $news)
    {
        return view('admin.news.form', ['item' => $news]);
    }

    public function update(Request $request, News $news)
    {
        $news->update($this->validated($request, $news));

        return redirect()->route('admin.news.index')->with('success', 'Новину оновлено.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return back()->with('success', 'Новину видалено.');
    }

    private function validated(Request $request, ?News $news = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160', 'alpha_dash', Rule::unique('news', 'slug')->ignore($news?->id)],
            'body' => ['required', 'string', 'max:20000'],
            'publish' => ['nullable', 'boolean'],
        ]);

        $given = $data['slug'] ?? null;
        $slug = $given ?: Str::slug(Str::transliterate($data['title'])) ?: Str::lower(Str::random(8));
        if (! $given && News::where('slug', $slug)->when($news, fn ($q) => $q->where('id', '!=', $news->id))->exists()) {
            $slug .= '-'.Str::lower(Str::random(4));
        }

        return [
            'title' => $data['title'],
            'slug' => $slug,
            'body' => $data['body'],
            'published_at' => $request->boolean('publish') ? ($news?->published_at ?? now()) : null,
        ];
    }
}
