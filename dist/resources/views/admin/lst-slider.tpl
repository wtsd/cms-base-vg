{extends file="index.tpl"}

{block name="content-wrapper"}
    <a href="/{$prefix}/{$ctype}/add/" class="btn btn-primary pull-right">Новый слайдер</a>

<h2>Слайдеры</h2>
{include file='paginator.tpl'}

<form name="frm_lst" method="post" data-model="common\Slider">

<table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>id</th>
    <th>Название</th>
    <th>Статус</th>
    <th>Фото</th>
    <th>Ссылка</th>
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
    <td>
    {if $record.status > 0}
    <i class="fa fa-eye-slash"></i>
    {else}
    <i class="fa fa-check-circle-o"></i>
    {/if}
    </td>
    <td>
        {if $record.images|@count > 0}
            <a class="btn btn-default btn-sm" data-placement="bottom" data-html="true" data-popover-content="#images-{$record.id}" data-toggle="popover" href="#" tabindex="0">
                <i class="fa fa-picture-o"></i>
            </a>

            {include file="includes/images-popover.tpl"
                id=$record.id
                images=$record.images
                imgPrefix="/img/slider/"
                isDirect=true
                }
        {/if}
    </td>
    <td>
        {$record.uri}
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
    
</div>
{/block}