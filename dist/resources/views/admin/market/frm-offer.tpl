{extends file="index.tpl"}

{block name="content-wrapper"}
<h2>Товар</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li><a href="/{$prefix}/offer/browse/" class="btn_tolst">Все товары</a></li>
  {if $id > 0}
  <li class="active">Редактирование товара «{$obj.name|htmlspecialchars}» (id: {$id})</li>
  {else}
  <li class="active">Добавление товара</li>
  {/if}
</ul>

<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" name="frm_offer" role="form">
<input type="hidden" name="act" value="ajax" />
<input type="hidden" name="controller" value="save" />
<input type="hidden" name="model" value="market\Offer" />
<input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
<input type="hidden" name="id" value="{$id}" id="id" />
<input type="hidden" class="form-control" name="cdate" value="{$obj.cdate|htmlspecialchars}" id="cdate">

<div class="alert messagebox" role="alert"></div>
{if $id > 0 && $id != $obj.id}
<div class="alert alert-danger">
  Ошибка загрузки товара!
</div>
{/if}
<div class="frm frm-offer">

  <div class="form-group">
    {include file='includes/frm-panel.tpl' save='Сохранить' list='Список товаров' add='Создать товар' view='Посмотреть'}
  </div>

<div class="row">
  <div class="col col-md-8">
    <div class="row">
      <div class="col col-md-6">
        <div class="form-group">
          <label for="pcat_id">Тип товара</label>
          <select name="pcat_ids[]" class="form-control" multiple id="pcat_id">
            <option value="">---</option>
            {foreach from=$pcategories item=option}
              <option value="{$option.id}"{if $option.id|in_array:$obj.pcats} selected="selected"{/if}>{$option.name}</option>
            {/foreach}
          </select>
        </div>
      </div>
      <div class="col col-md-6">
        <div class="form-group">
          <label for="name">Название</label>
            <input type="text" class="form-control" name="name" placeholder="Название товара" required value="{$obj.name|htmlspecialchars}" id="name">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col col-md-12">
        <div class="form-group-ta">
            <textarea name="descr" class="editor" id="descr">{$obj.descr}</textarea>
        </div>
        

    <div role="tabpanel">

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist" id="recordTabs">
        <li role="presentation" class="active">
          <a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">
          SEO
          </a>
        </li>
        <li role="presentation">
          <a href="#properties" aria-controls="properties" role="tab" data-toggle="tab">
            Характеристики
          </a>
        </li>
        <li role="presentation">
          <a href="#misc" aria-controls="misc" role="tab" data-toggle="tab">
            Прочее
          </a>
        </li>
      </ul>
      
      <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="seo">
              <div class="panel panel-default">
                <div class="panel-heading">SEO</div>
                <div class="panel-body">
                  {include file="includes/seo-frm.tpl"}
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="properties">
              <div class="panel panel-default">
                <div class="panel-heading">Характеристики</div>
                <div class="panel-body">
                  {if isset($specs)}
                    {if isset($specs_info)}
                      {include file='includes/specs.tpl' specs=$specs row=$specs_info}
                    {else}
                      {include file='includes/specs.tpl' specs=$specs}
                    {/if}
                  {/if}
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="misc">
              <div class="panel panel-default">
                <div class="panel-heading">Прочее</div>
                <div class="panel-body">
                  <div class="form-group-ta">
                    <label for="comment">Комментарий:</label><br>
                      <textarea name="comment" id="comment" class="form-control">{$obj.comment}</textarea>
                      <small>Комментарий для внутреннего пользования (нигде на сайте не отображается).</small>
                  </div>


                  <div class="row">
                    <div class="col-md-4">
                    
                        <div class="form-group">
                          <input type="checkbox" name="is_special" {if $obj.is_special == 1}checked="checked"{/if} id="is_special">
                          <label for="is_special">
                            На главную
                          </label>
                        </div>
                      
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <input type="checkbox" name="is_recommended" {if $obj.is_recommended == 1}checked="checked"{/if} id="is_recommended">
                        <label for="is_recommended">
                          Рекомендованный
                        </label>
                      </div>
                      
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="ord">Порядок:</label>
                          <input type="text" class="form-control" name="ord" placeholder="" value="{$obj.ord|htmlspecialchars}" id="ord">
                          <small>Порядковый номер.</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
      </div>

    </div>
      </div>      
    </div>

  </div>
  <div class="col col-md-4">
    
    <div class="row">
      <div class="col col-md-6">
        <div class="form-group">

          <label for="price">Цена</label>
          <div class="input-group">
            <input type="text" class="form-control" name="price" placeholder="" value="{$obj.price|htmlspecialchars}" id="price">
            <span class="input-group-addon" title="Цена">руб</span>
          </div>

        </div>
        
      </div>
      <div class="col col-md-6">
        <div class="form-group">
          <label for="name">Ссылка</label>
          <div class="input-group">
            <span class="input-group-addon" title="SEO Ссылка">
              <i class="fa fa-external-link" aria-hidden="true"></i>
            </span>
            <input type="text" class="form-control" name="rewrite" placeholder="nazvanie" required value="{$obj.rewrite|htmlspecialchars}" id="rewrite">
          </div>
        </div>
      </div>      
      <div class="col col-md-12">
        
        <div class="form-group">
          <label for="vendor_id">Производитель</label>
          <select name="vendor_id" class="form-control" id="vendor_id">
              <option>--</option>
            {foreach from=$vendors item=row}
              <option value="{$row.id}" {if $row.id == $obj.vendor_id} selected="selected"{/if}>{$row.name}</option>
            {/foreach}
          </select>
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col col-md-12">
        
        <div class="form-input">
          <label for="image">Загрузить фотографии:</label>
          <span class="btn btn-default btn-file">
            <i class="fa fa-upload"></i> Загрузить фотографии…
            <input type="file" name="image" multiple class="upload-input" data-url="/{$prefix}/ajax/uploadFile/offer/{$id|htmlspecialchars}/image/" data-type="image"><br>
          </span>
          <progress style="display:none;"></progress>
        </div>

        <div class="photos">
          {if isset($photos) && count($photos) > 0}
          {foreach from=$photos item=photo}
          <div class="single-photo{if $photo.is_main == 1} is_main{/if}" data-id="{$photo.id}">
            <span class="photo-toolbar" style="display:none;">
              <a href="#" class="delete"><span class="glyphicon glyphicon-trash"></span></a>
              <a href="/img/offer/{$id}/full/{$photo.fname}" class="open" target="_blank"><span class="glyphicon glyphicon-zoom-in"></span></a>
              <a href="#" class="rotate"><span class="glyphicon glyphicon-repeat"></span></a>
              <a href="#" class="is_main" data-action="main"{if $photo.is_main == 1} style="display:none;"{/if}>MAIN</a>
            </span>
            <a href="/img/offer/{$id}/full/{$photo.fname}" target="_blank" data-id="{$photo.id}">
              <img src="/img/offer/{$id}/full/{$photo.fname}" class="image" alt="" id="img{$photo.id}">
            </a>
          </div>
          {/foreach}
          {/if}
          
        </div>
          <div class="form-group">
            <label for="status">Статус:</label>
              <select name="status" class="form-control">
                <option value="1"{if $obj.status == 1} selected="selected"{/if}>опубликован</option>
                <option value="0"{if $obj.status == 0} selected="selected"{/if}>скрыт</option>
              </select>
          </div>
      </div>

    </div>
  </div>
</div>
<div class="row">
  <div class="col col-md-8">
    
</div>
</div>

    
  <div class="form-group">
    {include file='includes/frm-panel.tpl' save='Сохранить' list='Список товаров' add='Создать товар' view='Посмотреть'}
  </div>
</div>
</form>
{/block}