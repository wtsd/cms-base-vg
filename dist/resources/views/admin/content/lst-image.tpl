{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Изображения</h2>
{include file='paginator.tpl'}

<form name="frm_lst" method="post" data-model="content\Article">

<table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>id</th>
    <th>Фото</th>
    <th>Галерея</th>
    <th>Подпись</th>
    <th>Дата загрузки</th>
    <th></th>
 </tr>
 {foreach from=$records item=record}
 <tr>
    <td><a href="/{$prefix}/{$ctype}/edit/{$record.id}/">{$record.id}</a></td>
    <td><a href="/img/gallery/{$record.gal_id}/full/{$record.fname}" target="_blank"><img src="/img/gallery/{$record.gal_id}/thumb/{$record.fname}" width="50" /></a></td>
    <td>{$record.gal_id}</td>
    <td>{$record.name}</td>
    <td>{$record.cdate|date_format:'Y-m-d H:i'}</td>
    <td>
    {*if $record.status > 0}
    <i class="fa fa-eye-slash"></i>
    {else}
    <i class="fa fa-check-circle-o"></i>
    {/if*}
    </td>

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
    <p>Изображения попадают в галерею, где их можно просмотреть сгруппированными, либо увеличить и просмотреть каждую фотографию отдельно.</p>
</div>
{/block}