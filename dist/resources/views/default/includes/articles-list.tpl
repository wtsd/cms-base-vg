<div class="articles">
{foreach from=$articles item=art}
    <div class="article-preview">
        <h3><a href="/article/{$art.rewrite}/">{$art.name}</a></h3>
        {$art.lead}
        {if $art.f_text != ''}
        <a href="/article/{$art.rewrite}/">Читать полностью</a>
        {/if}
        <div class="meta-info"><div class="time">Дата публикации: {$art.cdate}</div></div>
        {include file="includes/tags.tpl" tags=$art.tags}
    </div>
{/foreach}
</div>

{include file="includes/paginator.tpl"}
