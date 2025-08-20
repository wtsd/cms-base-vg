{extends file="index.tpl"}

{block name="content-wrapper"}
<h2>Тип товара</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li><a href="/{$prefix}/pcategory/browse/">Все типы товара</a></li>
  {if $id > 0}
  <li class="active">Редактирование типа товара «{$obj.name|htmlspecialchars}» (id: {$id})</li>
  {else}
  <li class="active">Добавление типа товара</li>
  {/if}
</ul>

<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" class="frm" name="frm_pcategory" role="form">
<input type="hidden" name="act" value="ajax" />
<input type="hidden" name="controller" value="save" />
<input type="hidden" name="model" value="market\PCategory" />
<input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
<input type="hidden" name="id" value="{$id}" id="id" />

<div class="alert messagebox"></div>

<div class="frm">
  <div class="form-group">
    <button class="btn_save btn btn-primary"><span class="glyphicon glyphicon-save"></span> Сохранить</button>
    <button class="btn_tolst btn btn-default"><span class="glyphicon glyphicon-th-list"></span> Список типов</button>
    <button class="btn_addanother btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Создать новый тип</button>
    {if $obj.rewrite != ''} <a href="/products/{$obj.rewrite|htmlspecialchars}" target="_blank" class="btn btn-info"><span class="glyphicon glyphicon-eye-open"></span> Посмотреть</a>{/if}
  </div>
  <div class="form-group">
    <label for="name">Название:</label>
      <input type="text" class="form-control" name="name" placeholder="Название типа" required value="{$obj.name|htmlspecialchars}" id="name">
      <small>Отображение типа в каталоге, на странице товара и так далее.</small>
  </div>
  <div class="form-group">
    <label for="name">Ссылка:</label>
      <input type="text" class="form-control" name="rewrite" placeholder="nazvanie-tipa" required value="{$obj.rewrite|htmlspecialchars}" id="rewrite">
      <small>Для ссылки формата http://domain.tld/products/<em>galname</em>{if $obj.rewrite != ''} <a href="/products/{$obj.rewrite|htmlspecialchars}" target="_blank">→</a>{/if}</small>
  </div>
  <div class="form-group-ta">
    <label for="descr">Описание:</label><br>
      <textarea name="descr" class="editor" id="descr">{$obj.descr}</textarea>
      <small>При открытии списка товаров в этой категории, текст будет отображаться перед списком.</small>
  </div>
  <div class="form-group-ta">
    <label for="post_desc">Описание:</label><br>
      <textarea name="post_desc" class="editor" id="post_desc">{$obj.post_desc}</textarea>
      <small>При открытии списка товаров в этой категории, текст будет отображаться после списка.</small>
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
    <label for="pcat_id">Родительский раздел:</label>
    <select name="pcat_id" class="form-control" id="pcat_id">
      <option value="">---</option>
      {foreach from=$pcategories item=option}
        <option value="{$option.id}"{if $option.id == $obj.pcat_id} selected="selected"{/if}>{$option.name}</option>
      {/foreach}
    </select>
  </div>
  <div class="form-group">
    <label for="cdate">Время создания категории:</label>
      <input type="text" class="form-control" name="cdate" placeholder="2014-01-20 16:20:00" required value="{$obj.cdate|htmlspecialchars}" id="cdate">
      <small>Время публикации.</small>
  </div>
  <div class="form-group">
    <label for="ord">Порядок:</label>
      <input type="text" class="form-control" name="ord" placeholder="" value="{$obj.ord|htmlspecialchars}" id="ord">
      <small>Порядковый номер.</small>
  </div>

    <div class="photos">
    {if $obj.images}
    <input type="hidden" name="submodel" value="Market\PCategoryImage" id="submodel">
    {assign var="subdir" value="pcategory"}
    {foreach from=$obj.images item=photo}
    <div class="single-photo" data-id="{$photo.id}">
      <span class="photo-toolbar" style="display:none;">
        <a href="#" class="delete"><span class="glyphicon glyphicon-trash"></span></a>
        <a href="/img/{$subdir}/{$id}/full/{$photo.fname}" class="open" target="_blank"><span class="glyphicon glyphicon-zoom-in"></span></a>
        <a href="#" class="rotate"><span class="glyphicon glyphicon-repeat"></span></a>
      </span>
      <a href="/img/{$subdir}/{$id}/full/{$photo.fname}" target="_blank" data-id="{$photo.id}">
        <img src="/img/{$subdir}/{$id}/full/{$photo.fname}" class="image" alt="" id="img{$photo.id}">
      </a>
    </div>
    {/foreach}
    {/if}
    </div>

      <div class="form-input">
        <label for="upload">Загрузить фотографии:</label>
        <input type="file" name="image" multiple class="upload-input" data-url="/{$prefix}/ajax/uploadFile/pcategory/{$id|htmlspecialchars}/image/" data-type="image"><br>
        <progress style="display:none;"></progress>
      </div>

    {include file="includes/seo-frm.tpl"}
    
  <div class="form-group">
    <button class="btn_save btn btn-primary"><span class="glyphicon glyphicon-save"></span> Сохранить</button>
    <button class="btn_tolst btn btn-default"><span class="glyphicon glyphicon-th-list"></span> Список типов</button>
    <button class="btn_addanother btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Создать новый тип</button>
    {if $obj.rewrite != ''} <a href="/products/{$obj.rewrite|htmlspecialchars}" target="_blank" class="btn btn-info"><span class="glyphicon glyphicon-eye-open"></span> Посмотреть</a>{/if}
  </div>
</div>
</form>

{/block}