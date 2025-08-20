{extends file="index.tpl"}

{block name="content-wrapper"}

{*
<ng-view></ng-view>
*}

<h2>Тип товара</h2>
{include file='paginator.tpl'}

<form name="frm_lst" method="post" data-model="market\Offer">

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
        {include file='includes/lst-pcategory-item.tpl' }
     {/foreach}
</tbody>
</table>
</form>
{include file='paginator.tpl'}

{/block}

{block name="right-navigation"}
<div class="row">
    {*<a href="#/addpcategory" class="btn btn-primary">Новый тип товара</a>*}
    <a href="/{$prefix}/{$ctype}/add/" class="btn btn-primary">Новый тип товара</a>
    <p>Разделение на категории товара осуществляется с помощью специального меню на сайте..</p>
</div>
{/block}