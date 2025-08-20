{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Мероприятие</h2>
<p class="pull-right">
    <a href="/{$prefix}/{$ctype}/add" class="btn btn-info">
        <i class="fa fa-plus"></i>
    </a>
</p>
{include file='paginator.tpl'}

<form name="frm_lst" method="post" data-model="event\Event">

<table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>id</th>
    <th>Название</th>
    <th>Дата создания</th>
    <th>Статус</th>
    <th></th>
 </tr>
</thead>
<tbody>
 {foreach from=$records item=record}
 <tr>
    <td><a href="/{$prefix}/{$ctype}/edit/{$record.id}/">{$record.id}</a></td>
    <td>
        <a href="/{$prefix}/{$ctype}/edit/{$record.id}/">
    {if $record.name!=''}
        {$record.name|stripslashes}
    {else}
        <em>[N/A]</em>
    {/if}
    </a>
    </td>
    <td>{$record.cdate|date_format:'Y-m-d H:i'}</td>
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
    <a href="/{$prefix}/{$ctype}/add/" class="btn btn-primary">Новое мероприятие</a>
    <p>Мероприятие отображается на сайте в виде страницы, на которой можно оставить заявку.</p>
</div>
{/block}