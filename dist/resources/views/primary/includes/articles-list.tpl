{strip}
{if isset($articles[0]) && $articles[0]|@count > 0}
<div class="articles">
{if isset($count)}
<div class="text-right text-muted small total">
    Всего: {$count|intval}
</div>
{/if}
{foreach from=$articles item=art}
    <div class="article-preview">
        <h3><a href="/article/{$art.rewrite}/">{$art.name}</a></h3>
        <div class="date">
            <i class="fa fa-calendar"></i>&nbsp;
            {*$art.cdate|date_format:'%A, %H:%M, %B %e, %Y'*}
            <time datetime="{$art.cdate}">{$art.cdate|date_format:'%d.%m.%Y'}</time>&nbsp;
            <i class="fa fa-eye"></i>&nbsp;
            <span class="views">{if $art.stats.views > 0}{$art.stats.views}{else}0{/if}</span>&nbsp;
            {*<i class="fa fa-comments"></i>&nbsp;
            <span class="comments">{$art.comment_count}</span>&nbsp;*}
            <i class="fa fa-user"></i>&nbsp;
            <span class="user"><a href="/user/{$art.user_id}/">{$art.username}</a></span>&nbsp;
            {if $art.cat_id > 0}
            <i class="fa fa-folder-open-o"></i>&nbsp;
            <span class="category"><a href="/category/{$art.cat_rewrite}/">{$art.cat_name}</a></span>&nbsp;
            {/if}
        </div>
        <div class="row">
            {if $art.with_images == 1}
                {if $art.photos|@count > 0}
                <div class="col col-md-3 col-sm-12">
                    {if $art.url !== ''}
                    <a href="{$art.url}">
                    {else}
                    <a href="/article/{$art.rewrite}/">
                    {/if}
                        <img src="/img/article/{$art.id}/thumb/{$art.photos[0].fname}" alt="{$art.name|htmlspecialchars}" class="img-responsive">
                    </a>
                </div>
                {/if}
            {/if}
            <div class="col col-md-{if $art.photos|@count > 0}9{else}12{/if}">
                {$art.lead|strip}
                {if $art.f_text != ''}
                <div class="readmore"><a href="/article/{$art.rewrite}/">Читать дальше!</a></div>
                {/if}
                {include file="includes/tags.tpl" tags=$art.tags}
            </div>
            
        </div>
    </div>
    <div class="clearfix"></div>
{/foreach}
</div>
{else}
<div class="alert alert-info" role="alert">
    Пока нет записей, но скоро точно будут!
</div>
{/if}

{include file="includes/paginator.tpl"}
{/strip}