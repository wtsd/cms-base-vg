{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Расписание</h2>
<p class="pull-right">
    <a href="/{$prefix}/{$ctype}/add">
        <i class="fa fa-plus"></i>
    </a>
</p>
{include file='paginator.tpl'}

<form name="frm_lst" method="post" data-model="event\Schedule">

<table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>id</th>
    <th>Мероприятие</th>
    <th>Время</th>
    <th>Цена</th>
    <th>Статус</th>
    <th></th>
 </tr>
</thead>
<tbody>
 {foreach from=$records item=record}
 <tr>
    <td><a href="/{$prefix}/{$ctype}/edit/{$record.id}/">{$record.id}</a></td>
    <td>{$record.event_name}</td>
    
    <td>
        <a href="/{$prefix}/{$ctype}/edit/{$record.id}/">
            {$labels.wdays[$record.wday]}, {$record.time|date_format:"H:i"}
        </a>
    </td>
    <td>
        {$record.price}
    </td>
    <td>

        {if $record.status == 0}
        <i class="fa fa-eye-slash"></i>
        {else}
        <i class="fa fa-check-circle-o"></i>
        {/if}

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
    <a href="/{$prefix}/{$ctype}/add/" class="btn btn-primary">Новая бронь</a>
    <p>Мероприятие отображается на сайте в виде страницы, на которой можно оставить заявку.</p>
</div>
{/block}