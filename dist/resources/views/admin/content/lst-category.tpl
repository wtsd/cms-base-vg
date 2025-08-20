{extends file="index.tpl"}

{block name="content-wrapper"}

<a href="/{$prefix}/{$ctype}/add/" class="btn btn-primary pull-right">Новый раздел</a>

<h2>Разделы сайта</h2>

{include file='paginator.tpl'}

<form name="frm_lst" method="post">
<table class="table table-striped table-hover" data-ctype="{$ctype}" data-model="content\Category">
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
    {include file='includes/lst-category-item.tpl' }
 {/foreach}
</tbody>
</table>
</form>
{include file='paginator.tpl'}

{/block}

{block name="right-navigation"}
<div class="row">
    <p>Раздел — это страница сайта, на которой представлена только основная информация. Из разделов формируется главное меню на сайте.</p>
</div>
{/block}