{extends file="index.tpl"}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/">Главная</a></li>
        {if $uri == '/gallery/'}
        <li class="active">Все галереи</li>
        {else}
        <li><a href="/gallery/">Все галереи</a></li>
        {/if}
        {if isset($gallery.name)}
        <li class="active">{$gallery.name}</li>
        {/if}
    </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row normal-block">
	{include file="includes/gallery-contents.tpl"}
</div>
{/block}

