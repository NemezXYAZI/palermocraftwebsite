{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($pages as $page)
    <url><loc>{{ $page['loc'] }}</loc><priority>{{ $page['priority'] }}</priority></url>
@endforeach
@foreach ($news as $item)
    <url><loc>{{ route('news.show', $item->slug) }}</loc><lastmod>{{ ($item->updated_at ?? $item->published_at)->toAtomString() }}</lastmod><priority>0.5</priority></url>
@endforeach
</urlset>
