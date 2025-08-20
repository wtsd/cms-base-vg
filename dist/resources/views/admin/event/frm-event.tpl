{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Мероприятие</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li><a href="/{$prefix}/{$c_type}/browse/">Все мероприятия</a></li>
  {if $id > 0}
  <li class="active">Редактирование мероприятия «{$obj.name|htmlspecialchars}» (id: {$obj.id})</li>
  {else}
  <li class="active">Добавление нового мероприятия</li>
  {/if}
</ul>

<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" role="form" name="frm frm_event">
  <input type="hidden" name="act" value="ajax" />
  <input type="hidden" name="controller" value="save" />
  <input type="hidden" name="model" value="event\Event" />
  <input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
  <input type="hidden" name="id" value="{$id}" id="id" />

  <div class="alert messagebox"></div>

  <div class="frm">
    <div class="form-group">
      {include file='includes/frm-panel.tpl' save='Сохранить' list='Список мероприятий' add='Создать новое' view='Посмотреть'}
    </div>

    <div class="form-group">
      <label for="name">Название:</label>
        <input type="text" class="form-control" name="name" placeholder="Название раздела" required value="{$obj.name|htmlspecialchars}" id="name">
        <small>Название будет использовано в пункте меню и в качестве заголовка.</small>
    </div>
    <div class="form-group">
      <label for="rewrite">Ссылка:</label>
        <input type="text" class="form-control" name="rewrite" placeholder="nazvanie-razdela" required value="{$obj.rewrite|htmlspecialchars}" id="rewrite">
        <small>Для ссылки формата http://domain.tld/event/<em>cat-name</em>{if $obj.rewrite != ''} <a href="/event/{$obj.rewrite|htmlspecialchars}" target="_blank">→</a>{/if}</small>
    </div>
    <div class="form-group-ta">
      <label for="descr">Основной текст:</label><br>
        <small>Текст записи, отображаемый после заголовка, лида и фото.</small>
        <textarea name="descr" class="editor" id="descr">{$obj.descr}</textarea>
    </div>


  <div role="tabpanel">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" id="recordTabs">
      <li role="presentation" class="active"><a href="#files" aria-controls="files" role="tab" data-toggle="tab">Файлы</a></li>
      <li role="presentation"><a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">SEO</a></li>
      <li role="presentation"><a href="#options" aria-controls="options" role="tab" data-toggle="tab">Опции</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="files">
        <div class="panel panel-default">
          <div class="panel-heading">Файлы и изображения</div>
          <div class="panel-body">

            

      <div class="form-input">
        <label for="upload">Загрузить фотографии:</label>
        <input type="file" name="image" multiple class="upload-input" data-url="/{$prefix}/ajax/uploadFile/event/{$id|htmlspecialchars}/image/" data-type="image"><br>
        <progress style="display:none;"></progress>
      </div>

              <div class="photos">
                {if isset($photos) && count($photos) > 0}
                <input type="hidden" name="submodel" value="Content\ArticleImage" id="submodel">
                {foreach from=$photos item=photo}
                <div class="single-photo" data-id="{$photo.id}">
                  <span class="photo-toolbar" style="display:none;">
                    <a href="#" class="delete"><span class="glyphicon glyphicon-trash"></span></a>
                    <a href="/img/event/{$id}/full/{$photo.fname}" class="open" target="_blank"><span class="glyphicon glyphicon-zoom-in"></span></a>
                    <a href="#" class="rotate"><span class="glyphicon glyphicon-repeat"></span></a>
                  </span>
                  <a href="/img/event/{$id}/full/{$photo.fname}" target="_blank" data-id="{$photo.id}">
                    <img src="/img/event/{$id}/full/{$photo.fname}" class="image" alt="" id="img{$photo.id}">
                  </a>
                </div>
                {/foreach}
                {/if}
              </div>
            </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="seo">
        <div class="panel panel-default">
          <div class="panel-heading">Поисковая оптимизация</div>
          <div class="panel-body">
            {include file="includes/seo-frm.tpl"}
          </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="options">
        <div class="panel panel-default">
          <div class="panel-heading">Дополнительная информация о записи</div>
          <div class="panel-body">
              <div class="col col-md-6">
                <div class="form-group">
                  <label for="cdate">Время создания категории:</label>
                    <input type="text" class="form-control" name="cdate" placeholder="2014-01-20 16:20:00" required value="{$obj.cdate|htmlspecialchars}" id="cdate">
                    <small>Время публикации.</small>
                </div>
              </div>
              <div class="col col-md-6">
                <div class="form-group">
                  <label for="status">Статус:</label>
                    <select name="status" class="form-control">
                      <option value="1"{if $obj.status == 1} selected="selected"{/if}>опубликован</option>
                      <option value="0"{if $obj.status == 0} selected="selected"{/if}>скрыт</option>
                    </select>
                    <small>Публиковать запись?</small>
                  </div>
                </div>
              </div>
          </div>
        </div>
    </div>
  </div>


    <div class="form-group">
      {include file='includes/frm-panel.tpl' save='Сохранить' list='Список мероприятий' add='Создать новое' view='Посмотреть'}
    </div>
  </div>
</form>

{/block}