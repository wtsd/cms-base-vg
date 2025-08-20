<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
{foreach from=$urls item=url}
  <url> 
    <loc>http://{$domain}/{$url.url}</loc> 
    <lastmod>{$url.cdate|date_format:"%Y-%m-%d"}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.00</priority>
  </url>
{/foreach}
</urlset>