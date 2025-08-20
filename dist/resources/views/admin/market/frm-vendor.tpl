{extends file="index.tpl"}

{block name="content-wrapper"}
<h2>Производитель</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li><a href="/{$prefix}/vendor/browse/">Все производители</a></li>
  {if $id > 0}
  <li class="active">Редактирование производителя «{$obj.name|htmlspecialchars}» (id: {$id})</li>
  {else}
  <li class="active">Добавление производителя</li>
  {/if}
</ul>

<form name="frm_{$c_type}" action="/{$prefix}/" method="post" enctype="multipart/form-data" id="frm" role="form">
<input type="hidden" name="act" value="ajax" />
<input type="hidden" name="controller" value="save" />
<input type="hidden" name="model" value="market\Vendor" />
<input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
<input type="hidden" name="id" value="{$id}" id="id" />

<div class="alert messagebox"></div>

<div class="frm">

  <div class="form-group">
    {include file='includes/frm-panel.tpl' save='Сохранить' list='Список производителей' add='Создать нового' view='Посмотреть'}
  </div>

  <div class="form-group">
    <label for="name">Название:</label>
      <input type="text" class="form-control" name="name" placeholder="Название типа" required value="{$obj.name|htmlspecialchars}" id="name">
      <small>Отображение типа в каталоге, на странице товара и так далее.</small>
  </div>

  <div class="form-group">
    <label for="name">SEO-ссылка:</label>
      <input type="text" class="form-control" name="rewrite" placeholder="rewrite" required value="{$obj.rewrite|htmlspecialchars}" id="rewrite">
      <small></small>
  </div>

  <div class="form-group-ta">
    <label for="descr">Описание:</label><br>
      <textarea name="descr" class="editor" id="descr">{$obj.descr}</textarea>
      <small>При открытии списка товаров в этой категории, текст будет отображаться перед списком.</small>
  </div>

  <div class="form-group">
    <label for="status">Статус:</label>
      <select name="status" class="form-control">
        <option value="1"{if $obj.status == 1} selected="selected"{/if}>опубликована</option>
        <option value="0"{if $obj.status == 0} selected="selected"{/if}>скрыта</option>
      </select>
      <small>Запись можно скрыть.</small>
  </div>

  <div class="form-group">
    <label for="cdate">Время создания:</label>
      <input type="text" class="form-control" name="cdate" placeholder="2014-01-20 16:20:00" required value="{$obj.cdate|htmlspecialchars}" id="cdate">
      <small>Время публикации.</small>
  </div>

  <div class="form-group">
    <label for="site">Ссылка на сайт:</label>
      <input type="url" class="form-control" name="site" placeholder="http://example.com/" required value="{$obj.site|htmlspecialchars}" id="site">
      <small>Отображение типа в каталоге, на странице товара и так далее.</small>
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