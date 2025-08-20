{extends file="index.tpl"}

{block name="content-wrapper"}

{*<h2>Пост</h2>*}


<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" role="form" name="frm frm_article">
  <input type="hidden" name="act" value="ajax" />
  <input type="hidden" name="controller" value="save" />
  <input type="hidden" name="model" value="content\Article" />
  <input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
  <input type="hidden" name="id" value="{$id}" id="id" />

  <input type="hidden" name="is_commented" checked="checked" id="is_commented">
  <input type="hidden" name="ord" value="0" id="ord">

  <div class="frm">
    <div class="form-group save-panel" data-spy="affix" data-offset-top="10">
      <div class="row">
        <div class="col col-md-4">
          {include file='includes/frm-panel.tpl' save='Сохранить' view='Посмотреть'}
          
        </div>
        <div class="col col-md-8">
          <ul class="breadcrumb">
            <li><a href="/{$prefix}/"><i class="fa fa-home"></i></a></li>
            <li><a href="/{$prefix}/article/browse/">Все посты</a></li>
            {if $id > 0}
            <li class="active">Редактирование поста «{$obj.name|htmlspecialchars}» (id: {$obj.id})</li>
            {else}
            <li class="active">Добавление нового поста</li>
            {/if}
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col col-md-9">
        <div class="row">
          <div class="col col-md-4">
            <div class="form-group">
              <label for="cat_id">Раздел:</label>
              <select name="cat_id" class="form-control" id="cat_id">
                {foreach from=$categories item=option}
                  <option value="{$option.id}"{if $option.id == $obj.cat_id} selected="selected"{/if}>{$option.name|htmlspecialchars}</option>
                {/foreach}
              </select>
            </div>
            
          </div>
          <div class="col col-md-8">
            <div class="form-group">
              <label for="name">Название:</label>
                <input type="text" class="form-control" name="name" placeholder="Название статьи" required="required" value="{$obj.name|htmlspecialchars}" id="name">
            </div>
            
          </div>
        </div>
        
        <div class="form-group-ta">
            <textarea name="lead" class="editor" id="lead">{$obj.lead}</textarea>
        </div>
        <div class="form-group-ta">
            <textarea name="f_text" class="editor" id="f_text" rows="20">{$obj.f_text}</textarea>
        </div>
      </div>
      <div class="col col-md-3">
        <div class="form-group form-group-sm">

          <label for="rewrite">ЧПУ:</label>
          <div class="input-group">
            <div class="input-group-addon">/article/</div>
            <input type="text" class="form-control" name="rewrite" placeholder="nazvanie-razdela" required="required" value="{$obj.rewrite|htmlspecialchars}" id="rewrite">
            <div class="input-group-addon">{if isset($obj.rewrite)}<a href="/article/{$obj.rewrite}/" target="_blank">→</a>{/if}</div>
          </div>
        </div>
        <div class="form-group">
          <label for="tags">Теги:</label>
            <input type="text" class="form-control" name="tags" placeholder="" required value="{$obj.tags|implode:', '|htmlspecialchars}" id="tags" data-role="tagsinput">
        </div>

        <div class="row">
          <div class="col col-md-7">
            <div class="form-group form-group-sm">
              <label for="cdate">Дата создания:</label>
                <input type="text" class="form-control" name="cdate" placeholder="2014-01-20 16:20:00" required value="{$obj.cdate|htmlspecialchars}" id="cdate">
            </div>
            
          </div>
          <div class="col col-md-5">
            <div class="form-group form-group-sm">
              <label for="status">Статус:</label>
                <select name="status" class="form-control">
                  <option value="0"{if $obj.status == 0} selected="selected"{/if}>опубликован</option>
                  <option value="1"{if $obj.status == 1} selected="selected"{/if}>скрыт</option>
                </select>
            </div>
            
          </div>
        </div>
        
        <div class="form-input">
          <span class="btn btn-default btn-file">
            <i class="fa fa-upload"></i> Загрузить фотографии…
            <input type="file" name="image" multiple class="upload-input" data-url="/{$prefix}/ajax/uploadFile/article/{$id|htmlspecialchars}/image/" data-type="image"><br>
          </span>
          <progress class="image" style="display:none;"></progress>
        </div>

        <div class="photos">
          {if isset($photos) && count($photos) > 0}
          <input type="hidden" name="submodel" value="Content\ArticleImage" id="submodel">
          {foreach from=$photos item=photo}
          <div class="single-photo" data-id="{$photo.id}">
            <span class="photo-toolbar" style="display:none;">
              <a href="#" class="delete"><span class="glyphicon glyphicon-trash"></span></a>
              <a href="/img/article/{$id}/full/{$photo.fname}" class="open" target="_blank"><span class="glyphicon glyphicon-zoom-in"></span></a>
              <a href="#" class="rotate"><span class="glyphicon glyphicon-repeat"></span></a>
              <a href="#" class="is_main" data-action="main"{if isset($photo.is_main) && $photo.is_main == 1} style="display:none;"{/if}>MAIN</a>
            </span>
            <a href="/img/article/{$id}/full/{$photo.fname}" target="_blank" data-id="{$photo.id}">
              <img src="/img/article/{$id}/full/{$photo.fname}" class="image" alt="" id="img{$photo.id}">
            </a>
          </div>
          {/foreach}
          {/if}
        </div>

        <div class="form-group">

          <select name="with_images" id="with_images" class="form-control">
            <option value="1"{if $obj.with_images == 1} selected="selected"{/if}>Прикрепить фотографию</option>
            <option value="0"{if $obj.with_images == 0} selected="selected"{/if}>Не прикреплять фотографию</option>
          </select>
        </div>
              
        <div class="form-group">
          <label for="url">URL</label>
          <input type="text" name="url" class="form-control" value="{$obj.url|htmlspecialchars}">
        </div>


      </div>
    </div>
    

  <div role="tabpanel">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" id="recordTabs">
      <li role="presentation" class="active">
        <a href="#files" aria-controls="files" role="tab" data-toggle="tab">
        Файлы
        </a>
      </li>
      <li role="presentation">
        <a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">
          SEO
        </a>
      </li>
      <li role="presentation">
        <a href="#related" aria-controls="related" role="tab" data-toggle="tab">
          Похожие
        </a>
      </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="files">
        <div class="panel panel-default">
          <div class="panel-heading">Файлы</div>
          <div class="panel-body">

                <div class="form-input">
                  <label for="file">Загрузить файлы:</label>
                  <input type="file" name="file" multiple class="upload-input" data-url="/{$prefix}/ajax/uploadFile/article/{$id|htmlspecialchars}/attachment/" data-type="attachment"><br>
                  <progress class="attachment" style="display:none;"></progress>
                </div>

              <div class="attachments">
                {if isset($attachments) && count($attachments) > 0}
                  {foreach from=$attachments item=attachment}
                  <div class="single-attachment" data-id="{$attachment.id}">
                    <a href="/uploads/article/{$id}/{$attachment.fname}" target="_blank" data-id="{$attachment.id}">
                      <div class="filetype {$attachment.filetype}" title="/uploads/article/{$id}/{$attachment.fname}"></div>
                      {$attachment.name}
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
      <div role="tabpanel" class="tab-pane" id="related">
        <div class="panel panel-default">
          <div class="panel-heading">Похожие посты</div>
          <div class="panel-body">
            <label for="related">Статьи</labels>
              <select name="related[]" multiple="multiple" class="form-control">
                {foreach from=$articles item=article}
                  {if $article.id != $obj.id}
                  <option value="{$article.id}"{if $related && $article.id|in_array:$related} selected="selected"{/if}>
                    {$article.name}
                  </option>
                  {/if}
                {/foreach}
              </select>
          </div>
        </div>
      </div>
    </div>
  </div>

    <div class="form-group">
      {include file='includes/frm-panel.tpl' save='Сохранить' view='Посмотреть'}
    </div>
  </div>
</form>
{/block}