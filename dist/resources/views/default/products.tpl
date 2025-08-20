{extends file="index.tpl"}

{* SEO blocks *}
{block name="metakeywords"}{if !$rewrite}{$obj.meta_keywords}{else}{$currentCategory.meta_keywords}{/if}{/block}

{block name="metadescription"}{if !$rewrite}{$obj.meta_description}{else}{$currentCategory.meta_description}{/if}{/block}


{block name="title"}{if isset($obj) && $obj.title != ''}{$obj.title}{else}{$labels.global_title}{/if}{/block}

{* /SEO blocks *}

{block name="content-breadcrumb"}{strip}
<div class="row">
    <ul class="breadcrumb">
        {foreach from=$breadcrumb_arr item=row key=i}
        <li{if $rewrite == $row.rewrite}  class="active"{/if}>
            {if $rewrite !== $row.rewrite}<a href="/products/{if isset($row.rewrite)}{$row.rewrite}/{/if}">{/if}
                {$row.name}
            {if $rewrite !== $row.rewrite}</a>{/if}
        </li>
        {/foreach}
    </ul>
</div>
{/strip}{/block}

{block name="content-wrapper"}{strip}

<div class="row normal-block">
    <div class="col-md-2">
        {include file="includes/offer-search-frm.tpl" query=""}
        {include file="includes/pcat-menu.tpl"}
    </div>

    <div class="col-md-10">
        <h1>{if !$rewrite}{$obj.h1}{else}{$currentCategory.h1}{/if}</h1>
        {if $currentCategory.name != '' and $currentCategory.descr != ''}
            {$currentCategory.descr}
    		<hr>
        {/if}
        {if $offers|@count > 0}
            <div>
            {foreach from=$offers item=offer}
                {include file='includes/single-offer.tpl'}
            {/foreach}
            </div>
        {else}
            <p>В настоящее время товар в категории отсутствует.</p>
        {/if}

        {if $currentCategory != ''}
        {include file="includes/paginator.tpl"}
        {/if}

    </div>
</div>
{/strip}{/block}