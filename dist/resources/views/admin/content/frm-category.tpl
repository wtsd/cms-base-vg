{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Раздел</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li><a href="/{$prefix}/category/browse/">Все разделы</a></li>
  {if $id > 0}
  <li class="active">Редактирование раздела «{$obj.name|htmlspecialchars}» (id: {$obj.id})</li>
  {else}
  <li class="active">Добавление нового раздела</li>
  {/if}
</ul>

<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" role="form" name="frm_category">
  <input type="hidden" name="act" value="ajax" />
  <input type="hidden" name="controller" value="save" />
  <input type="hidden" name="model" value="content\Category" />
  <input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
  <input type="hidden" name="id" value="{$id}" id="id" />

  <div class="alert messagebox"></div>

  <div class="frm">
    <div class="form-group">
      {include file='includes/frm-panel.tpl' save='Сохранить' list='Список разделов' add='Создать новый' view='Посмотреть'}
    </div>
    
    <div class="form-group">
      <label for="name">Название:</label>
        <input type="text" class="form-control" name="name" placeholder="Название раздела" required value="{$obj.name|htmlspecialchars}" id="name">
        <small>Название будет использовано в пункте меню и в качестве заголовка.</small>
    </div>
    <div class="form-group">
      <label for="rewrite">СЕО-ссылка:</label>
        <input type="text" class="form-control" name="rewrite" placeholder="nazvanie-razdela" required value="{$obj.rewrite|htmlspecialchars}" id="rewrite">
        <small>Для ссылки формата http://domain.tld/category/<em>cat-name</em>{if $obj.rewrite != ''} <a href="/category/{$obj.rewrite|htmlspecialchars}" target="_blank">→</a>{/if}</small>
    </div>
    <div class="form-group">
      <label for="url">URL:</label>
        <input type="text" class="form-control" name="url" placeholder="" value="{$obj.url|htmlspecialchars}" id="url">
        <small>Если пункт меню должен вести куда-то за пределы категории (/products/, /gallery/gallery-name/).</small>
    </div>
    <div class="form-group">
      <label for="title">Заголовок:</label>
        <input type="text" class="form-control" name="title" placeholder="Заголовок раздела" value="{$obj.title|htmlspecialchars}" id="title">
        <small>Заголовок для вкладки или окна, <em>&lt;title&gt;Заголовок раздела&lt;/title&gt;</em>.</small>
    </div>
    <div class="form-group">
      <label for="cat_id">Родительский раздел:</label>
      <select name="cat_id" class="form-control" id="cat_id">
        <option value="0">---</option>
        {foreach from=$categories item=option}
          <option value="{$option.id}"{if $option.id == $obj.cat_id} selected="selected"{/if}>{$option.name}</option>
        {/foreach}
      </select>
    </div>
    <div class="form-group-ta">
      <label for="lead">Лид:</label><br>
        <textarea name="lead" class="editor" id="lead">{$obj.lead}</textarea>
        <small>Лид, отображаемый перед основным текстом и в качестве превью записи.</small>
    </div>
    <div class="form-group-ta">
      <label for="f_text">Основной текст:</label><br>
        <textarea name="f_text" class="editor" id="f_text">{$obj.f_text}</textarea>
        <small>Текст записи, отображаемый после заголовка, лида и фото.</small>
    </div>
    <div class="form-group">
      <label for="gallery_id">Галерея:</label>
      <select name="gallery_id" class="form-control" id="gallery_id">
        <option value="0">---</option>
        {foreach from=$galleries item=option}
          <option value="{$option.id}"{if $option.id == $obj.gallery_id} selected="selected"{/if}>{$option.name}</option>
        {/foreach}
      </select>
    </div>
    <div class="form-group">
      <label for="cdate">Время создания категории:</label>
        <input type="text" class="form-control" name="cdate" placeholder="2014-01-20 16:20:00" required value="{$obj.cdate|htmlspecialchars}" id="cdate">
        <small>Время публикации.</small>
    </div>
    <div class="form-group">
      <label for="status">Статус:</label>
        <select name="status" class="form-control">
          <option value="0"{if $obj.status == 0} selected="selected"{/if}>опубликован</option>
          <option value="1"{if $obj.status == 1} selected="selected"{/if}>скрыт</option>
        </select>
        <small>Публиковать раздел?.</small>
    </div>
    <div class="form-group">
      <label for="tags">Теги:</label>
        <input type="text" class="form-control" name="tags" placeholder="" value="{$obj.tags|htmlspecialchars}" id="tags">
        <small>Метки для создания облака тегов и лучшего seo.</small>
    </div>
    <div class="form-group">
      <label for="ord">Порядок:</label>
        <input type="text" class="form-control" name="ord" placeholder="" value="{$obj.ord|htmlspecialchars}" id="ord">
        <small>Порядковый номер.</small>
    </div>
    {include file="includes/seo-frm.tpl"}

    <div class="form-group">
     {include file='includes/frm-panel.tpl' save='Сохранить' list='Список разделов' add='Создать новый' view='Посмотреть'}
    </div>
  </div>
</form>
{/block}