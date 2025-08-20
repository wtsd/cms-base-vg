{extends file="index.tpl"}

{block name="content-wrapper"}

<div class="row">
        <div class="col col-md-8">
            <form action="/{$prefix}/offer/browse/" method="get" name="frm_filter" class="form-inline">
                <div class="form-group">
                <a href="/{$prefix}/{$ctype}/frm/" class="btn btn-primary">
                    <i class="fa fa-plus-square"></i> 
                    Добавить товар
                </a>
                </div>
                <div class="form-group">
                <select name="pcat_id" id="pcat_id" class="form-control">
                    <option value="">-- Все товары --</option>
                {foreach from=$pcats item=pcategory}
                    <option value="{$pcategory.id}" {if $filters.pcat_id == $pcategory.id} selected="selected"{/if}>{$pcategory.name} ({$pcategory.offers_cnt})</option>
                {/foreach}
                </select>
                </div>
                <div class="form-group">
                    <select name="sortby" id="sortby" class="form-control">
                        <option value="id"{if $filters.sortby == 'id'} selected{/if}>По ID</option>
                        <option value="name"{if $filters.sortby == 'name'} selected{/if}>По названию</option>
                        <option value="cdate"{if $filters.sortby == 'cdate'} selected{/if}>По дате</option>
                        <option value="price"{if $filters.sortby == 'price'} selected{/if}>По цене</option>
                    </select>
                    <select name="sortdir" id="sortdir" class="form-control">
                        <option value="asc"{if $filters.sortdir == 'asc'} selected{/if}>A-Я</option>
                        <option value="desc"{if $filters.sortdir == 'desc'} selected{/if}>Я-А</option>
                    </select>
                </div>
                <button class="btn btn-default" title="Фильтровать">
                    <i class="fa fa-filter"></i>
                </button>
                <a href="/{$prefix}/offer/browse/" class="btn btn-danger" title="Снять фильтр">
                    <i class="fa fa-times"></i>
                </a>
            </form>
            
        </div>
        <div class="col col-md-4" style="text-align: right;">
            {*include file='paginator.tpl' *}

            {assign var="preUrlSprint" value=true}
            {if $pcat_id > 0}
            {assign var="preUrl" value='/adm/offer/browse/%d/?pcat_id='|cat:$pcat_id}
            {else}
            {assign var="preUrl" value='/adm/offer/browse/%d/'}
            {/if}
            {include file='includes/pagination.tpl' preUrlSprint=true}


        </div>
</div>
<hr>
<form name="frm_lst" method="post" data-model="market\Offer">

<table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>id</th>
    <th>Фото</th>
    <th>Название</th>
    <th>Создан</th>
    <th></th>
    <th>Цена</th>
    <th></th>
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
        <strong>{$record.name|stripslashes}</strong>
        </a>
    <br>
            Категории: 
            {foreach from=$record.pcats item=pcat}
                <a href="/{$prefix}/{$ctype}/browse/?pcat_id={$pcat.id}">
                <span class="label label-info">
                    {$pcat.name}
                </span>
                </a>
            &nbsp;
            {/foreach}
            <br>
        {*
        <a href="/{$prefix}/pcategory/edit/{$record.pcat_id}/">{$record.pcat_name}</a> »
        *}
        {if $record.vendor_id > 0}
        (<a href="/{$prefix}/vendor/edit/{$record.vendor_id}">
            {$record.vendor_name}
        </a>)
        {/if}
    </td>
    <td>{$record.cdate|date_format:'Y-m-d H:i'} {$record.username}</td>
    <td>
        {if $record.images|@count > 0}
            <a class="btn btn-default btn-sm"
            data-placement="bottom"
            data-html="true"
            data-popover-content="#images-{$record.id}"
            data-toggle="popover"
            rel="popover"
            href="#">
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
        {$record.price} руб.
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
        <a href="/{$prefix}/{$ctype}/edit/{$record.id}/" class="btn btn-success btn-xs">
            <i class="fa fa-pencil"></i>
        </a>
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
<div class="pull-right">
{include file='paginator.tpl'}
</div>


{/block}

{block name="right-navigation"}
<div class="row">
    <a href="/{$prefix}/{$ctype}/add/" class="btn btn-primary">Новый товар</a>
    <p>Карточка товара — это страница, на которой отображается короткая информация о товаре, комментарии, характеристики и фотографии.</p>
</div>
{/block}