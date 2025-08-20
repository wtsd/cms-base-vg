{extends file="index.tpl"}

{* SEO blocks *}
{*block name="metakeywords"}{/block*}

{*block name="metadescription"}{/block*}

{*block name="title"}{/block*}

{* /SEO blocks *}

{block name="content-breadcrumb"}{strip}
<div class="row">
  <ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i></a></li>
    <li><a href="/products/">Каталог товаров</a></li>
    <li class="active">Поиск товара</li>
  </ul>
</div>
{/strip}{/block}

{block name="content-wrapper"}{strip}
<div class="row normal-block">
    <div class="col-md-2">
        {include file="includes/offer-search-frm.tpl"}
        {include file="includes/pcat-menu.tpl"}
    </div>
    <div class="col-md-10">
        {if isset($offers) && $offers|@count > 0}
            <h3>Результаты поиска по слову «{$query|htmlspecialchars}»</h3>
            <div>
            {foreach from=$offers item=offer}
                {include file='includes/single-offer.tpl'}
            {/foreach}
            </div>
        {/if}

        {include file="includes/paginator.tpl"}
    </div>
</div>
{/strip}{/block}