{extends file="index.tpl"}

{block name="content-wrapper"}
<h2>Галерея</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a> </li>
  <li><a href="/{$prefix}/gallery/browse/">Все галереи</a> </li>
  {if $id > 0}
  <li class="active">Редактирование галереи «{$obj.name|htmlspecialchars}» (id: {$id})</li>
  {else}
  <li class="active">Добавление новой галереи</li>
  {/if}
</ul>

<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" name="frm_gallery">
<input type="hidden" name="act" value="ajax" />
<input type="hidden" name="controller" value="save" />
<input type="hidden" name="model" value="content\Gallery" />
<input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
<input type="hidden" name="id" value="{$id}" id="id" />
<div class="alert messagebox"></div>

<div class="frm frm-gallery">
  <div class="form-group">
    {include file='includes/frm-panel.tpl' save='Сохранить' list='Список' add='Создать' view='Посмотреть'}
  </div>

  <div class="row">
    <div class="col col-md-4">
      <div class="form-group">
        <select name="gal_id" id="gal_id" class="form-control">
        {foreach from=$galleries item=gal}
        <option value="{$gal.id|htmlspecialchars}"{if $gal.id == $obj.gal_id} selected="selected"{/if}>
          {$gal.name}
        </option>
        {/foreach}
        </select>
      </div>
      
    </div>
    <div class="col col-md-4">
      <div class="form-group">
        <label for="name" class="sr-only">Название:</label>
          <input type="text" class="form-control" name="name" placeholder="Название альбома" required value="{$obj.name|htmlspecialchars}" id="name">
      </div>
      
    </div>
    <div class="col col-md-4">
      
      <div class="form-group">
        <label for="name" class="sr-only">Ссылка:</label>

    <div class="input-group">
      <div class="input-group-addon">
        /gallery/
      </div>
      <input type="text" class="form-control" name="rewrite" placeholder="nazvanie-alboma" required value="{$obj.rewrite|htmlspecialchars}" id="rewrite" placeholder="Читаемая ссылка">
      <div class="input-group-addon">
        /
      </div>

    </div>
  
      </div>
      
    </div>
    <div class="col col-md-8">

      <div class="form-input">
        <label for="upload">Загрузить фотографии:</label>
        <input type="file" name="image" multiple class="upload-input" data-url="/{$prefix}/ajax/uploadImage/gallery/{$id|htmlspecialchars}/image/" data-type="image"><br>
        <progress style="display:none;"></progress>
      </div>

      <div class="photos">
        <input type="hidden" name="submodel" value="content\Image" id="submodel">
        {foreach from=$photos item=photo}
        <div class="single-photo" data-id="{$photo.id}">
          <span class="photo-toolbar" style="display:none;">
            <a href="#" class="delete"><span class="glyphicon glyphicon-trash"></span></a>
            <a href="/{$prefix}/image/edit/{$photo.id}" class="edit" target="_blank"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="/img/gallery/{$id}/full/{$photo.fname}" class="open" target="_blank"><span class="glyphicon glyphicon-zoom-in"></span></a>
            <a href="#" class="rotate"><span class="glyphicon glyphicon-repeat"></span></a>
          </span>
          <a href="/{$prefix}/image/edit/{$photo.id}" data-id="{$photo.id}">
            <img src="/img/gallery/{$id}/full/{$photo.fname}" class="image" alt="" id="img{$photo.id}">
          </a>
        </div>
        {/foreach}
      </div>
      <div class="form-group">
        <label for="lead">Описание:</label><br>
          <textarea name="lead" class="editor" id="lead">{$obj.lead}</textarea>
          <small>При открытии галереи, этот текст будет отображаться над списком фотографий.</small>
      </div>
    </div>
    <div class="col col-md-4">
      <div class="form-group">
      <label for="is_active">Статус:</label>
        <select name="is_active" class="form-control">
          <option value="0"{if $obj.is_active == 0} selected="selected"{/if}>опубликована</option>
          <option value="1"{if $obj.is_active == 1} selected="selected"{/if}>скрыта</option>
        </select>
        <small>Если галерею не должны видеть посетители сайта в какое-то время, её можно просто скрыть.</small>
      </div>
      <div class="form-group">
        <label for="tags">Теги:</label>
          <input type="text" class="form-control" name="tags" placeholder="" required value="{$obj.tags|htmlspecialchars}" id="tags" data-role="tagsinput">
          <small>Метки для создания облака тегов и лучшего seo.</small>
      </div>
        
    </div>
  </div>
    {include file="includes/seo-frm.tpl"}
  <div class="form-input">
    {include file='includes/frm-panel.tpl' save='Сохранить' list='Список' add='Создать' view='Посмотреть'}
  </div>

</div>
</form>

{/block}