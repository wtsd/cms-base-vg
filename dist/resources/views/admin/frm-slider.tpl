{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Слайдер</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li><a href="/{$prefix}/article/browse/">Все слайдеры</a></li>
  {if $id > 0}
  <li class="active">Редактирование слайдера «{$obj.name|htmlspecialchars}» (id: {$obj.id})</li>
  {else}
  <li class="active">Добавление нового слайдера</li>
  {/if}
</ul>

<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" role="form" name="frm frm_slider">
  <input type="hidden" name="act" value="ajax" />
  <input type="hidden" name="controller" value="save" />
  <input type="hidden" name="model" value="common\Slider" />
  <input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
  <input type="hidden" name="id" value="{$id}" id="id" />
  <div class="alert messagebox"></div>

  <div class="frm">
    <div class="form-group">
      {include file='includes/frm-panel.tpl' save='Сохранить' list='Список слайдеров' add='Создать новый'}
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="name">Название:</label>
            <input type="text" class="form-control" name="name" placeholder="Название" required value="{$obj.name|htmlspecialchars}" id="name">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="uri">Ссылка:</label>
            <input type="text" class="form-control" name="uri" placeholder="/" required value="{$obj.uri|htmlspecialchars}" id="uri">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
            <label for="status">Статус:</label>
              <select name="status" class="form-control">
                <option value="0"{if $obj.status == 0} selected="selected"{/if}>опубликован</option>
                <option value="1"{if $obj.status == 1} selected="selected"{/if}>скрыт</option>
              </select>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-input">
                <label for="image">Загрузить фотографии:</label>
                <input type="file" name="image" multiple class="upload-input" data-url="/{$prefix}/ajax/uploadFile/slider/{$id|htmlspecialchars}/image/" data-type="image"><br>
                <progress class="image" style="display:none;"></progress>
              </div>

              <div class="photos">
                {if isset($photos) && count($photos) > 0}
                <input type="hidden" name="submodel" value="Common\Slide" id="submodel">
                {foreach from=$photos item=photo}
                <div class="single-photo" data-id="{$photo.id}">
                  <span class="photo-toolbar" style="display:none;">
                    <a href="#" class="delete"><span class="glyphicon glyphicon-trash"></span></a>
                    <a href="/img/slider/{$id}/{$photo.fname}" class="open" target="_blank"><span class="glyphicon glyphicon-zoom-in"></span></a>
                    <a href="#" class="rotate"><span class="glyphicon glyphicon-repeat"></span></a>
                  </span>
                  <a href="/img/slider/{$id}/{$photo.fname}" target="_blank" data-id="{$photo.id}">
                    <img src="/img/slider/{$id}/{$photo.fname}" class="image" alt="" id="img{$photo.id}">
                  </a>
                </div>
                {/foreach}
                {/if}
              </div>
      </div>
    </div>
  </div>


    <div class="form-group">
      {include file='includes/frm-panel.tpl' save='Сохранить' list='Список слайдеров' add='Создать новый'}
    </div>
  </div>
</form>
{/block}