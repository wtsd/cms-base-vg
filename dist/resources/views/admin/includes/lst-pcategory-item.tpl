 <tr>
	<td>{$record.id}</td>
	<td><a href="/{$prefix}/{$ctype}/edit/{$record.id}/">{$record.name}</a></td>
	<td>{$record.cdate|date_format:'Y-m-d H:i'}</td>
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
        <a href="/products/{$record.rewrite}/" class="btn btn-info btn-xs" target="_blank">
            <i class="fa fa-eye"></i>
        </a>
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
			{include file='includes/lst-pcategory-item.tpl' record=$subcat}
		{/foreach}
		</tbody>
	</table>
	</td>
</tr>
{/if}

