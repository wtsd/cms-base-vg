{extends file="index.tpl"}

{block name="content-wrapper"}
<h2>Характеристика товара</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li><a href="/{$prefix}/pcategory/browse/">Все характеристики товара</a></li>
  {if $id > 0}
  <li class="active">Редактирование характеристики товара «{$obj.name|htmlspecialchars}» (id: {$id})</li>
  {else}
  <li class="active">Добавление характеристики товара</li>
  {/if}
</ul>

<form name="frm_{$c_type}" action="/{$prefix}/" method="post" enctype="multipart/form-data" id="frm" role="form">
<input type="hidden" name="act" value="ajax" />
<input type="hidden" name="controller" value="save" />
<input type="hidden" name="model" value="market\PSpec" />
<input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
<input type="hidden" name="id" value="{$id}" id="id" />

<div class="alert messagebox"></div>

<div class="frm">
  <div class="form-group">
    <button class="btn_save btn btn-primary"><span class="glyphicon glyphicon-save"></span> Сохранить</button>
    <button class="btn_tolst btn btn-default"><span class="glyphicon glyphicon-th-list"></span> Список</button>
    <button class="btn_addanother btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Создать</button>
    {if isset($obj.rewrite) && $obj.rewrite != ''} <a href="/{$c_type}/{$obj.rewrite|htmlspecialchars}" target="_blank" class="btn btn-info"><span class="glyphicon glyphicon-eye-open"></span> Посмотреть</a>{/if}
  </div>

  <div class="form-group">
    <label for="name">Название:</label>
      <input type="text" class="form-control" name="name" placeholder="Название типа" required value="{$obj.name|htmlspecialchars}" id="name">
      <small>Отображение типа в каталоге, на странице товара и так далее.</small>
  </div>

  <div class="form-group">
    <label for="pcat_id">Тип товара:</label>
    <select name="pcat_id" class="form-control" id="pcat_id">
      <option value="">---</option>
      {foreach from=$pcategories item=option}
        <option value="{$option.id}"{if $option.id == $obj.pcat_id} selected="selected"{/if}>{$option.name}</option>
      {/foreach}
    </select>
  </div>

  <div class="form-group">
    <label for="stype">Тип характеристики:</label>
    <select name="stype" class="form-control" id="stype">
      <option value="">---</option>
      {foreach from=$stypes item=option}
        <option value="{$option.id}"{if $option.id == $obj.stype} selected="selected"{/if}>{$option.name}</option>
      {/foreach}
    </select>
  </div>

  <div class="form-group">
    <label for="values">Значения:</label>
      <input type="text" class="form-control" name="values" placeholder="Название типа" required value="{$obj.values|htmlspecialchars}" id="values">
      <small>Возможные значения</small>
  </div>

  <div class="form-group">
    <label for="defval">Значение по умолчанию:</label>
      <input type="text" class="form-control" name="defval" placeholder="Название типа" required value="{$obj.defval|htmlspecialchars}" id="defval">
      <small>Значение</small>
  </div>

  <div class="form-group">
    <label class="checkbox"><input type="checkbox" name="required" value="1"{if $obj.required == 1} checked="checked"{/if}> <label for="required">Обязательное поле</label>
  </div>

  <div class="form-group">
    <label for="status">Статус:</label>
      <select name="status" class="form-control">
        <option value="0"{if $obj.status == 0} selected="selected"{/if}>опубликована</option>
        <option value="1"{if $obj.status == 1} selected="selected"{/if}>скрыта</option>
      </select>
      <small>Запись можно скрыть.</small>
  </div>

<div class="form-group">
    <label for="ord">Порядок:</label>
      <input type="text" class="form-control" name="ord" placeholder="Порядок" required value="{$obj.ord|htmlspecialchars}" id="ord">
      <small>Значение</small>
  </div>


  <div class="form-group">
    <button class="btn_save btn btn-primary"><span class="glyphicon glyphicon-save"></span> Сохранить</button>
    <button class="btn_tolst btn btn-default"><span class="glyphicon glyphicon-th-list"></span> Список</button>
    <button class="btn_addanother btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Создать</button>
    {if isset($obj.rewrite) && $obj.rewrite != ''} <a href="/{$c_type}/{$obj.rewrite|htmlspecialchars}" target="_blank" class="btn btn-info"><span class="glyphicon glyphicon-eye-open"></span> Посмотреть</a>{/if}
  </div>
</div>
</form>

{/block}