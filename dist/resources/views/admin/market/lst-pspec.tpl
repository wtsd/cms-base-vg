{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Характеристика товара</h2>
{include file='paginator.tpl'}

<form name="frm_lst" method="post" data-model="market\PSpec">

<table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>id</th>
    <th>Название</th>
    <th>Тип</th>
    <th>Тип товара</th>
    <th>Значения</th>
    <th>По умолчанию</th>
    <th>Обязательная</th>
    <th>Дата</th>
    <th>Статус</th>
    <th>Порядок</th>
    <th></th>
 </tr>
</thead>
<tbody>
 {foreach from=$records item=record}
 <tr>
    <td><a href="/{$prefix}/{$ctype}/edit/{$record.id}/">{$record.id}</a></td>
    <td><a href="/{$prefix}/{$ctype}/edit/{$record.id}/">
    {if $record.name!=''}
        {$record.name|stripslashes}
    {else}
        <em>[N/A]</em>
    {/if}
    </a></td>
    <td>{$record.stype}</td>
    <td><a href="/{$prefix}/category/edit/{$record.pcat_id}/">{$record.pcat_name}</a></td>
    <td>{$record.values}</td>
    <td>{$record.defval}</td>
    <td>{$record.required}</td>
    <td>{$record.cdate|date_format:'Y-m-d H:i'}</td>
    <td>
    {if $record.status > 0}
    <i class="fa fa-eye-slash"></i>
    {else}
    <i class="fa fa-check-circle-o"></i>
    {/if}
    </td>
    <td>{$record.ord}</td>
    <td>
        <a href="/{$prefix}/{$ctype}/delete/{$record.id}/" class="delete btn btn-danger btn-xs" data-id="{$record.id}">
            <i class="fa fa-trash-o"></i>
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
    <a href="/{$prefix}/{$ctype}/add/" class="btn btn-primary">Новый параметр</a>
    <p>Характеристика товара — это параметр, который можно заполнить непосредственно для единицы товара и показать посетителю страницы карты товара подробную таблицу с самым полным описанием.</p>
</div>
{/block}