{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Клиенты</h2>
<p class="pull-right">
Записей: {$cnt}
</p>

<div class="row">
	<div class="col-md-9">
		{include file='paginator.tpl'}
	</div>
	<div class="col-md-3">
		<a href="/adm/clients/emails/" class="btn btn-default">
			<i class="fa fa-download" aria-hidden="true"></i> Все почтовые адреса
		</a>
	</div>
</div>	
<hr>
<form action="/adm/clients/browse/" method="get">
<div class="row">
	
	<div class="col-md-3">
		<input type="text" class="form-control" name="email" value="{$filter.email}" placeholder="Email">
	</div>
	<div class="col-md-3">
		<input type="text" class="form-control" name="name" value="{$filter.name}" placeholder="Имя">
	</div>
	<div class="col-md-3">
		<input type="text" class="form-control" name="tel" value="{$filter.tel}" placeholder="Телефон">
	</div>
	<div class="col-md-3">
		<button role="submit" class="btn btn-default">
			<i class="fa fa-filter"></i>
		</button>
		<a href="/adm/clients/browse/" class="btn btn-danger">
			<i class="fa fa-remove"></i>
		</a>
	</div>
</div>
</form>

<form name="frm_lst" method="post">
<table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>Email</th>
    <th>Имя</th>
    <th>Телефон</th>
 </tr>
</thead>
<tbody>
 {foreach from=$records item=record}
 <tr>
    <td>{$record.email}</td>
    
    <td>{$record.name}</td>
    <td>{$record.tel}</td>
 </tr>
 {/foreach}
</tbody>
</table>
</form>
{include file='paginator.tpl'}

{/block}
