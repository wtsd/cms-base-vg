{extends file="index.tpl"}

{block name="content-wrapper"}

<div class="row">
        <div class="col col-md-4">
        <a href="/{$prefix}/{$ctype}/frm/" class="btn btn-primary">
            <i class="fa fa-plus-square"></i> 
            Добавить товар
        </a>
        </div>
        <div class="col col-md-4">
            <form action="/{$prefix}/offer/browse/" method="get" name="frm_filter" class="form-inline">
                <div class="form-group">
                <select name="pcat_id" id="pcat_id" class="form-control">
                    <option value="">-- Все товары --</option>
                {foreach from=$pcats item=pcategory}
                    <option value="{$pcategory.id}" {if $pcat_id == $pcategory.id} selected="selected"{/if}>{$pcategory.name} ({$pcategory.offers_cnt})</option>
                {/foreach}
                </select>
                </div>
                <button class="btn btn-default">
                    <i class="fa fa-filter"></i>
                </button>
            </form>
            
        </div>
        <div class="col col-md-4">
            {include file='paginator.tpl'}
        </div>
</div>
<h2>Товар</h2>

<form name="frm_lst" method="post" data-model="market\Offer">

<table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>id</th>
    <th>Фото</th>
    <th>Название</th>
    <th>Дата создания</th>
    <th>Фото</th>
    <th>Спец / Статус / Реком</th>
    <th></th>
 </tr>
</thead>
<tbody>
 {foreach from=$records item=record}
 <tr>
    <td><a href="/{$prefix}/{$ctype}/edit/{$record.id}/">{$record.id}</a></td>
    <td>
        {if $record.fname != ''}
        <a href="/img/offer/{$record.id}/full/{$record.fname}" target="_blank">
            <img src="/img/offer/{$record.id}/thumb/{$record.fname}" width="50">
        </a>
        {/if}
    </td>
    <td>
        <a href="/{$prefix}/{$ctype}/edit/{$record.id}/">
    {if $record.name!=''}
        <strong>{$record.name|stripslashes}</strong>
    {else}
        <em>[N/A]</em>
    {/if}
    </a>
        {if $record.vendor_id > 0}
        (<a href="/{$prefix}/vendor/edit/{$record.vendor_id}">
            {$record.vendor_name}
        </a>)
        {/if}
        <br>
            Категории: 
            {foreach from=$record.pcats item=pcat}
            <span class="badge">
                {$pcat.name}
            </span>
            {/foreach}
            <br>
    </td>
    <td>{$record.cdate|date_format:'Y-m-d H:i'}</td>
    <td>
        {if $record.images|@count > 0}
            <a class="btn btn-default btn-sm" data-placement="bottom" data-html="true" data-popover-content="#images-{$record.id}" data-toggle="popover" href="#" tabindex="0">
                <i class="fa fa-picture-o"></i>
            </a>

            {include file="includes/images-popover.tpl"
                id=$record.id
                images=$record.images
                imgPrefix="/img/offer/"
                }
        {/if}
    </td>
    <td>

        <a href="#" data-action="toggle-status" data-ctype="offer" data-id="{$record.id}" data-status="{$record.status}" class="btn btn-sx btn-default">
        {if $record.status == 0}
        <i class="fa fa-eye-slash"></i>
        {else}
        <i class="fa fa-check-circle-o"></i>
        {/if}
        </a>
        
    </td>
    
    <td>
        <a href="/{$prefix}/{$ctype}/delete/{$record.id}/" class="delete btn btn-danger btn-xs" data-id="{$record.id}">
            <i class="fa fa-trash-o"></i>
        </a>
        <a href="/{$ctype}/{$record.rewrite}/" class="btn btn-info btn-xs" target="_blank">
            <i class="fa fa-eye"></i>
        </a>
    </td>
 </tr>
 {/foreach}
</tbody>
</table>
</form>
{include file='paginator.tpl'}

{/block}

{block name="right-navigation"}
<div class="row">
    <p>Карточка товара — это страница, на которой отображается короткая информация о товаре, комментарии, характеристики и фотографии.</p>
</div>
{/block}