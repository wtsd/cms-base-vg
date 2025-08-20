<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
    <title>{$labels.rss.title}</title>
    <link>{$labels.rss.url}</link>
    <description>{$labels.rss.descr}</description>
    <language>{$labels.rss.lang}</language>
    <pubDate>{$contents.pdate}</pubDate>
    <lastBuildDate>{$contents.ldate}</lastBuildDate>
    <docs>{$labels.rss.docs}</docs>
    <generator>{$labels.rss.generator}</generator>
    <managingEditor>{$labels.rss.memail}</managingEditor>
    <webMaster>{$labels.rss.wemail}</webMaster>
 {foreach from=$items item=item}
	<item>
      <title>{$item.title}</title>
      <link>{$item.link}</link>
      <description><![CDATA[{$item.lead}]]></description>
      <pubDate>{$item.mdate}</pubDate>
      <guid>{$item.guid}</guid>
    </item>
{/foreach}
</channel>
</rss>
