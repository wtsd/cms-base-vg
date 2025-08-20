{extends file="index.tpl"}

{block name="content-wrapper"}
<h2>Изображения</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li><a href="/{$prefix}/image/browse/">Все изображения</a></li>
  {if $id > 0}
  <li class="active">Редактирование изображения (id: {$id})</li>
  {else}
  <li class="active">Добавление нового изображения</li>
  {/if}
</ul>

<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" name="frm_image">
<input type="hidden" name="act" value="ajax" />
<input type="hidden" name="controller" value="save" />
<input type="hidden" name="model" value="content\Image" />
<input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
<input type="hidden" name="id" value="{$id}" id="id" />

<div class="alert messagebox"></div>

<div class="frm frm-gallery">
  <div class="form-group">
    {include file='includes/frm-panel.tpl' save='Сохранить' list='Список' add='Создать' view='Посмотреть'}
  </div>
  <div class="form-group">
    <label for="gal_id">Галерея:</label>
    <select name="gal_id" class="form-control" id="gal_id">
      <option>---</option>
      {foreach from=$galleries item=option}
        <option value="{$option.id}"{if $option.id == $obj.gal_id} selected="selected"{/if}>{$option.name}</option>
      {/foreach}
    </select>
  </div>
  <div class="form-group">
    <label for="name">Подпись:</label>
      <input type="text" class="form-control" name="name" placeholder="Подпись к фотографии" required value="{$obj.name|htmlspecialchars}" id="name">
      <small>Подпись изображения при открытии большой картинки.</small>
  </div>
  
  <div class="form-group">
    <label for="tags">Метки:</label>
      <input type="text" class="form-control" name="tags" placeholder="фотография, товар, продукт, услуга" required value="{$obj.tags|htmlspecialchars}" id="tags">
      <small></small>
  </div>
  <div class="form-group">
    {if $obj.fname != ''}
    <input type="hidden" name="fname" value="{$obj.fname|htmlspecialchars}">
    <div class="img">
      <a href="/img/gallery/{$obj.gal_id}/full/{$obj.fname}" target="_blank">
        <img src="/img/gallery/{$obj.gal_id}/thumb/{$obj.fname}" alt="">
      </a>
    </div>
    <div class="tools">
      [rotate]
    </div>
    {else}
      [IMAGE UPLOAD]
    {/if}
  </div>
  <div class="form-group">
    <label for="cdate">Подпись:</label>
      <input type="text" class="form-control" name="cdate" placeholder="" required value="{$obj.cdate|htmlspecialchars}" id="cdate">
      <small></small>
  </div>

  <div class="form-group">
    {include file='includes/frm-panel.tpl' save='Сохранить' list='Список' add='Создать' view='Посмотреть'}
  </div>
</div>
</form>

{/block}