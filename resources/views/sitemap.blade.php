@use('App\Support\Locale')
{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($pages as $page)
@foreach (array_keys(Locale::SUPPORTED) as $code)
    <url>
        <loc>{{ $code === Locale::DEFAULT ? $page['loc'] : $page['loc'].'?lang='.$code }}</loc>
@foreach (array_keys(Locale::SUPPORTED) as $alt)
        <xhtml:link rel="alternate" hreflang="{{ $alt }}" href="{{ $alt === Locale::DEFAULT ? $page['loc'] : $page['loc'].'?lang='.$alt }}"/>
@endforeach
        <priority>{{ $page['priority'] }}</priority>
    </url>
@endforeach
@endforeach
@foreach ($news as $item)
    <url><loc>{{ route('news.show', $item->slug) }}</loc><lastmod>{{ ($item->updated_at ?? $item->published_at)->toAtomString() }}</lastmod><priority>0.5</priority></url>
@endforeach
</urlset>
