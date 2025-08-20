{extends file="index.tpl"}
{assign var="titleHeader" value="Пользователи"}

{block name="title"}{$titleHeader}{/block}

{block name="content-wrapper"}{strip}
<h2>{$titleHeader}</h2>

<div class="row">
  {include file="includes/floating-btn.tpl" title="Добавить пользователя"}
  <div class="col-md-12">
    {include file="includes/messages.tpl"}

    {assign var="theaders" value=['ID', 'Email', 'Имя', 'Комментарий', 'Группа', 'Телефон']}
    {include file="includes/grid.tpl" type="users" theaders=$theaders}

  </div>
</div>

{include file="user/frm.tpl" frm_name="add-user" action="/adm/user/" obj=$empty_object id=$empty_object.id}

{/strip}{/block}