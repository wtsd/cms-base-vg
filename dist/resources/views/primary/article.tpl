{extends file="index.tpl"}

{block name="metakeywords"}{$obj.meta_keywords}{/block}
{block name="metadescription"}{$obj.meta_description}{/block}

{block name="title"}{if isset($obj)}{$obj.title}{else}{$labels.global_title}{/if}{/block}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i></a> </li>
        {if $obj.cat_id > 0}
        <li><a href="/category/{$obj.cat_rewrite}/">{$obj.cat_name}</a> </li>
        {/if}

        <li class="active">{$obj.name}</li>
    </ul>
</div>
{/block}

{block name="content-wrapper"}

{if isset($obj) && $obj|@count > 0}

<div class="row article normal-block">
    <div class="date">
        <a href="/article/{$obj.rewrite}" name="top">
            <span class="glyphicon glyphicon-calendar"></span>
            {$obj.cdate|date_format:'%A, %H:%M, %B %e, %Y'}
        </a> 
        <span class="glyphicon glyphicon-eye-open"></span>
            {$obj.stats.views}
    </div>

    {if $obj.h1 != ''}
    <h1>{$obj.h1}</h1>
    {/if}
    
    {if $obj.lead != ''}
        <div class="lead">{$obj.lead}</div>
    {/if}
    {if $obj.with_images == 1}
        {if $obj.photos|@count > 0}
            <div class="article-image"><img src="/img/article/{$obj.photos[0].art_id}/full/{$obj.photos[0].fname}" alt="{$obj.name|htmlspecialchars}" /></div>
        {/if}
    {/if}
    {if $obj.f_text != ''}
        <div class="article-text">{$obj.f_text}</div>
    {/if}
    {if $obj.with_images == 1}
        <div class="images">
            {if isset($obj.photos)}
                {foreach from=$obj.photos item=image}
                <div class="single-image">
                    <a href="/img/article/{$obj.id}/full/{$image.fname}" target="_blank">
                        <img src="/img/article/{$obj.id}/thumb/{$image.fname}" alt="">
                    </a>
                </div>
                {/foreach}
            {/if}
        </div>
    {/if}

    {if $obj.tags|@count > 0}
        {include file="includes/tags.tpl" tags=$obj.tags}
    {/if}

    {if $obj.attachments|@count > 0}
        <div class="clearfix"></div>
        <div class="attachments-head">
            {$labels.article.attachments}
        </div>
        <div class="attachments">
            {foreach from=$obj.attachments item=attachment}
            <div class="single-attachment">
                <a href="/uploads/article/{$obj.id}/{$attachment.fname}" target="_blank" data-id="{$attachment.id}" title="{$attachment.name}">
                    <div class="filetype {$attachment.filetype}" title="/uploads/article/{$obj.id}/{$attachment.fname}"></div>
                    {$attachment.name}
                </a>
            </div>
            {/foreach}
        </div>
    {/if}

    {include file="includes/share.tpl"}


    <nav>
      <ul class="pager">
        {if $prev}
        <li class="previous"><a href="/article/{$prev.rewrite}/" rel="prev" title="{$prev.name|htmlspecialchars}" role="prev"><span aria-hidden="true">&larr;</span> {$prev.name}</a></li>
        {else}
        <li class="previous disabled"><a href="#"><span aria-hidden="true">&larr;</span></a></li>
        {/if}
        {if $next}
        <li class="next"><a href="/article/{$next.rewrite}/" rel="next" title="{$next.name|htmlspecialchars}" role="next">{$next.name} <span aria-hidden="true">&rarr;</span></a></li>
        {else}
        <li class="next disabled"><a href="#"><span aria-hidden="true">&rarr;</span></a>
        {/if}
      </ul>
    </nav>



    {if $obj.related}
    <div class="clearfix"></div>
    <div class="related">
        <h4>Рекомендуемые статьи</h4>
        <ul>
        {foreach from=$obj.related item=relatedart}
            <li><a href="/article/{$relatedart.rewrite}/">{$relatedart.name}</a></li>
        {/foreach}
        </ul>
    </div>
    {/if}
</div>
{/if}

{/block}