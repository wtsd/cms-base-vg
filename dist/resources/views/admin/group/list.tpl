{extends file="index.tpl"}
{assign var="titleHeader" value="Группы"}

{block name="title"}{$titleHeader}{/block}

{block name="content-wrapper"}{strip}
<h2>{$titleHeader}</h2>

<div class="row">
  {include file="includes/floating-btn.tpl" title="Добавить группу"}
  <div class="col-md-12">
    {include file="includes/messages.tpl"}

    {assign var="theaders" value=['ID', 'Название', 'Комментарий', 'Статус']}
    {include file="includes/grid.tpl" type="groups" theaders=$theaders}

  </div>
</div>

{include file="group/frm.tpl" frm_name="add-group" action="/adm/group/" obj=$empty_object id=$empty_object.id}

{/strip}{/block}