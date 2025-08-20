{extends file="index.tpl"}

{block name="title"}{$vendor.title} - {$title}{/block}

{block name="content-breadcrumb"}{strip}
<div class="row">
    <ul class="breadcrumb">
      <li><a href="/"><i class="fa fa-home"></i></a></li>
      <li><a href="/products/">Каталог товаров</a></li>
      <li class="active">Производитель «{$vendor.name}»</li>
    </ul>
</div>
{/strip}{/block}

{block name="content-wrapper"}{strip}

<div class="row normal-block">
    <h1>{$vendor.name}</h1>
    <div class="col col-md-6">
        {$vendor.descr}
        <a href="{$vendor.site|htmlspecialchars}" rel="nofollow">
            {$vendor.site}
        </a>
    </div>
    <div class="col col-md-6">
      {foreach from=$offers item=offer}
      {include file="includes/single-offer.tpl"}
      {/foreach}
    </div>
</div>

{/strip}{/block}
