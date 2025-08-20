{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Время</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li><a href="/{$prefix}/{$c_type}/browse/">Все слоты</a></li>
  {if $id > 0}
  <li class="active">Редактирование слота «{$labels.wdays[$obj.wday]|htmlspecialchars} {$obj.time|date_format:"H:i"}» (id: {$obj.id})</li>
  {else}
  <li class="active">Добавление нового слота</li>
  {/if}
</ul>

<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" role="form" name="frm frm_{$c_type}">
  <input type="hidden" name="act" value="ajax" />
  <input type="hidden" name="controller" value="save" />
  <input type="hidden" name="model" value="event\Slot" />
  <input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
  <input type="hidden" name="id" value="{$id}" id="id" />

  <div class="alert messagebox"></div>

  <div class="frm">
    <div class="form-group">
      {include file='includes/frm-panel.tpl' save='Сохранить' list='Список слотов' add='Создать новый'}
    </div>

    <div class="form-group">
      <label for="event_id">Мероприятие</label>
      <select name="event_id" id="event_id" class="form-control">
        <option value="">---</option>
        {foreach from=$events item=event}
          <option value="{$event.id}"{if $event.id == $obj.event_id} selected="selected"{/if}>
            {$event.name|htmlspecialchars}
          </option>
        {/foreach}
      </select>
    </div>
    <div class="row">
      <div class="col col-md-4">
        <div class="form-group">
          <label for="wday">День недели:</label>
            <select name="wday" class="form-control">
              {foreach from=$labels.wdays item=wday key=i}
              <option value="{$i}"{if $obj.wday == $i} selected="selected"{/if}>{$i} {$wday}</option>
              {/foreach}
            </select>
        </div>
      </div>
      <div class="col col-md-2">
        <div class="form-group">
          <label for="time">Время:</label>
            <input type="text" class="form-control" name="time" placeholder="Время" required value="{$obj.time|htmlspecialchars}" id="name">
        </div>
      </div>
      <div class="col col-md-2">
        <div class="form-group">
          <label for="price">Цена:</label>
            <input type="text" class="form-control" name="price" placeholder="Цена" required value="{$obj.price|htmlspecialchars}" id="name">
        </div>
      </div>
      <div class="col col-md-4">
        <div class="form-group">
          <label for="status">Статус:</label>
            <select name="status" class="form-control">
              <option value="0"{if $obj.status == 0} selected="selected"{/if}>скрыт</option>
              <option value="1"{if $obj.status == 1} selected="selected"{/if}>опубликован</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    
    
    
    


    <div class="form-group">
      {include file='includes/frm-panel.tpl' save='Сохранить' list='Список слотов' add='Создать новый'}
    </div>
  </div>
</form>

{/block}