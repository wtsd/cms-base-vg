{extends file="index.tpl"}

{block name="metakeywords"}{$obj.meta_keywords}{/block}
{block name="metadescription"}{$obj.meta_description}{/block}

{block name="title"}{if isset($obj)}{$obj.title}{else}{$labels.global_title}{/if}{/block}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/">Главная</a> </li>
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
    <div class="date">Дата публикации: <a href="/article/{$obj.rewrite}" name="top">{$obj.cdate_rus}</a></div>

    <h1>{$obj.h1}</h1>
    {if $obj.lead != ''}
        <div class="lead">{$obj.lead}</div>
    {/if}
    {if $obj.photos|@count > 0}
        <div class="article-image"><img src="/img/article/{$obj.photos[0].art_id}/full/{$obj.photos[0].fname}" alt="{$obj.name|htmlspecialchars}" /></div>
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
        <div class="attachments-head">
            Приложения к статье:
        </div>
        <div class="attachments">
            {foreach from=$obj.attachments item=attachment}
            <div class="single-attachment">
                <a href="/uploads/article/{$obj.id}/{$attachment.fname}" target="_blank" data-id="{$attachment.id}">
                    <div class="filetype {$attachment.filetype}" title="/uploads/article/{$obj.id}/{$attachment.fname}"></div>
                    {$attachment.name}
                </a>
            </div>
            {/foreach}
        </div>
    {/if}

    {include file="includes/share.tpl"}
</div>
{/if}

{/block}