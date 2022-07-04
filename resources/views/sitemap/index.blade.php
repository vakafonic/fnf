<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($sitemap_array as $sitemaps)
    <url>
        @foreach($sitemaps as $key => $sitemap)
        <{{ $key }}>{{ $sitemap }}</{{ $key }}>
        @endforeach
    </url>
    @endforeach
</urlset>