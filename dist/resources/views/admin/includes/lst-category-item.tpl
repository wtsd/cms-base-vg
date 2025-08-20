 <tr>
    <td><a href="/{$prefix}/{$ctype}/edit/{$record.id}/">{$record.id}</a></td>
    <td>
        {if $record.cat_id > 0}
        <a href="/{$prefix}/{$ctype}/edit/{$record.cat_id}">{$record.parent_name}</a> » 
        {/if}
    {if $record.name!=''}
        <a href="/{$prefix}/{$ctype}/edit/{$record.id}/">{$record.name|stripslashes}</a>
    {else}
        <a href="/{$prefix}/{$ctype}/edit/{$record.id}/"><em>[N/A]</em></a>
    {/if}
    </td>
    <td>{$record.cdate|date_format:'Y-m-d H:i'}</td>
    <td>
        {if $record.status == 0}
        <i class="fa fa-check-circle-o"></i>
        {else}
        <i class="fa fa-eye-slash"></i>
        {/if}
    </td>

<td>
    <a href="/{$prefix}/{$ctype}/delete/{$record.id}/" class="delete btn btn-danger btn-xs" data-id="{$record.id}"><i class="fa fa-trash-o"></i></a>
    <a href="{if $record.url !== ''}{$record.url}{else}/{$ctype}/{$record.rewrite}/{/if}" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
</td>
</tr>
{if count($record.subcats) > 0}
<tr class="subcats">
    <td colspan="{$fields|count}">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th colspan="{$fields|count}">
                    <a href="#" class="toggle-tbody">&uarr;</a>
                </th>
            </tr>
        </thead>
        <tbody>
        {foreach from=$record.subcats item=subcat}
            {include file='includes/lst-category-item.tpl' record=$subcat}
        {/foreach}
        </tbody>
    </table>
    </td>
</tr>
{/if}

