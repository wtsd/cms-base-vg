{extends file="index.tpl"}
{assign var="titleHeader" value="Филиалы"}

{block name="title"}{$titleHeader}{/block}

{block name="content-wrapper"}{strip}
<h2>{$titleHeader}</h2>

<div class="row">
  {include file="includes/floating-btn.tpl" title="Добавить филиал"}
  <div class="col-md-12">
    {include file="includes/messages.tpl"}

    {assign var="theaders" value=['ID', 'Название', 'Адрес', 'Публичный', 'Активный', 'Телефон']}
    {include file="includes/grid.tpl" type="branches" theaders=$theaders}

  </div>
</div>

{include file="branch/frm.tpl" frm_name="add-branch" action="/adm/branch/" obj=$empty_object id=$empty_object.id}

{/strip}{/block}