{extends file="index.tpl"}

{block name="content-wrapper"}
    <a href="/{$prefix}/{$ctype}/add/" class="btn btn-primary pull-right">Новая статья</a>

<h2>Статьи</h2>
{include file='paginator.tpl'}

<form name="frm_lst" method="post" data-model="content\Article">

<table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>id</th>
    <th>Заголовок</th>
    <th>Раздел</th>
    <th>Дата создания</th>
    <th>Изображения</th>
    <th>Статус</th>
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
    <td><a href="/{$prefix}/category/edit/{$record.cat_id}/">{$record.cat_name}</a></td>
    <td>{$record.cdate|date_format:'Y-m-d H:i'}</td>
    <td>
        {if $record.images|@count > 0}
            <a class="btn btn-default btn-sm" data-placement="bottom" data-html="true" data-popover-content="#images-{$record.id}" data-toggle="popover" href="#" tabindex="0">
                <i class="fa fa-picture-o"></i>
            </a>

            {include file="includes/images-popover.tpl"
                id=$record.id
                images=$record.images
                imgPrefix="/img/article/"
                }
        {/if}
    </td>
    <td>
    {if $record.status > 0}
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

    <p>Статья — это страница, которая публикуется в Разделе и содержит текст, изображения, теги и приложения.</p>
    <p>Каждая статья будет иметь ссылку формата /article/nazvanie-statyi/, чтобы поисковые системы лучше индексировали содержащуюся в них информацию.</p>
</div>
{/block}