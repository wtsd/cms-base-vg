{extends file="index.tpl"}

{block name="content-wrapper"}

  <ul class="breadcrumb">
    <li><a href="/{$prefix}/">Начало</a></li>
    <li><a href="/{$prefix}/{$c_type}/browse/">Все бронирования</a></li>
    {if $id > 0}
    <li class="active">Редактирование брони #{$obj.id}</li>
    {else}
    <li class="active">Добавление нового бронирования</li>
    {/if}
  </ul>

<form enctype="multipart/form-data" action="/{$prefix}/" method="post" id="frm" role="form" name="frm frm_schedule">
  <input type="hidden" name="act" value="ajax" />
  <input type="hidden" name="controller" value="save" />
  <input type="hidden" name="model" value="event\Schedule" />
  <input type="hidden" name="c_type_str" value="{$c_type}" id="c_type_str" />
  <input type="hidden" name="id" value="{$id}" id="id" />


  <div class="frm">
    <div class="form-group">
      <div class="row">
        <div class="col col-md-4">
          {include file='includes/frm-panel.tpl' save='Сохранить'}
          
        </div>
        <div class="col col-md-4 alert-box">
        </div>
        <div class="col col-md-4">
          <p class="text-muted pull-right" title="Дата создания: {$obj.cdate|htmlspecialchars}">
              <i class="fa fa-clock-o"></i>
               {$obj.cdate|date_format:'M d H:i'}
          </p>
          <input type="hidden" class="form-control" name="cdate" value="{$obj.cdate|htmlspecialchars}" id="cdate" disabled="disabled">
        </div>
      </div>

    </div>

    <div class="row">
      <div class="col col-md-4">
        <div class="form-group">
          <select name="event_id" class="form-control" id="event_id">
            <option value="">--- Мероприятие ---</option>
            {foreach from=$events item=option}
              <option value="{$option.id}"{if $option.id == $obj.event_id || $events|@count == 1} selected="selected"{/if}>{$option.name}</option>
            {/foreach}
          </select>
        </div>
      </div>
      <div class="col col-md-2">
        <div class="form-group">

            <div class="input-group">
              <div class="input-group-addon datepicker-icon" title="Дата проведения"><i class="fa fa-calendar"></i></div>
              <input type="text" class="form-control datepicker" name="date"  value="{$obj.date|date_format:"Y-m-d"}" placeholder="Дата игры" autocomplete="off"  id="date">
            </div>
        </div>
      </div>
      <div class="col col-md-2">
        <div class="form-group">

            <div class="input-group">
              <div class="input-group-addon datepicker-icon" title="Время проведения"><i class="fa fa-clock-o"></i></div>
          {assign var=wday value=$obj.date|date_format:'%u'}
          {assign var="prevWday" value="null"}
          <select name="slot_id" class="form-control" id="slot_id">
            <option value="">---</option>
            {foreach from=$slots item=option}
            {if $prevWday != $option.wday}
              {if $prevWday != "null"}
              </optgroup>
              {/if}
              {assign var="prevWday" value=$option.wday}
              <optgroup label="{$labels.wdays_short[$option.wday]}" data-wday="{$option.wday}" data-event_id="{$option.event_id}"{if $wday != $option.wday} style="display:none;"{/if}>
            {/if}
              <option value="{$option.id}"{if $option.id == $obj.slot_id} selected="selected"{/if} data-price="{$option.price}" data-slot_id="{$option.id}">{$labels.wdays_short[$option.wday]} {$option.time|date_format:"H:i"}</option>
            {/foreach}
          </select>
        </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col col-md-2">
        <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon datepicker-icon"><i class="fa fa-user"></i></div>
              <input type="text" class="form-control" name="name" placeholder="Имя клиента" required value="{$obj.name|htmlspecialchars}" id="name">
            </div>
        </div>
      </div>
      <div class="col col-md-2">
        <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon datepicker-icon"><i class="fa fa-phone"></i></div>
              <input type="text" class="form-control" name="tel" placeholder="+79123456789" required value="{$obj.tel|htmlspecialchars}" id="tel">
            </div>
        </div>
      </div>
      <div class="col col-md-2">
        <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon datepicker-icon"><i class="fa fa-envelope"></i></div>
              <input type="email" class="form-control" name="email" placeholder="Email" value="{$obj.email|htmlspecialchars}" id="email">
            </div>
        </div>
      </div>
      <div class="col col-md-2">
        <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon datepicker-icon" title="Участников"><i class="fa fa-users"></i></div>

              <select name="participants" class="form-control" id="participants">
                {for $part=2 to 6}
                <option value="{$part}"{if $part == $obj.participants} selected="selected"{/if}>{$part} чел.</option>
                {/for}
              </select>
            </div>

        </div>
      </div>
    </div>

    <div class="row">
      <div class="col col-md-2">
        <div class="form-group">
          <label for="price">Стоимость:</label>

            <div class="input-group">
              <input type="text" class="form-control" name="price" placeholder="2500" required value="{$obj.price|htmlspecialchars}" id="price">
              <div class="input-group-addon">руб</div>
            </div>

            <small>C учётом скидки и налога.</small>
        </div>
      </div>
      
      <div class="col col-md-2">
        <div class="form-group">
          <label for="status">Статус:</label>
            <select name="status" class="form-control">
              <option value="0"{if $obj.status == 0} selected="selected"{/if}>завершён</option>
              <option value="1"{if $obj.status == 1} selected="selected"{/if}>новый</option>
              <option value="2"{if $obj.status == 2} selected="selected"{/if}>отменён</option>
            </select>
          </div>
      </div>
      <div class="col col-md-4">
        <div class="form-group-ta">
          <label for="comment">Комментарий:</label><br>
            <textarea name="comment" id="comment" class="form-control" rows="4">{$obj.comment}</textarea>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col col-md-2">

        <div class="form-group">
          <label for="winner">Победитель:</label>
            <input type="text" class="form-control" name="winner" placeholder="Имя победителя" value="{$obj.winner|htmlspecialchars}" id="winner">
        </div>
        
      </div>
      <div class="col col-md-2">
        
        <div class="form-group">
          <label for="score">Результат:</label>
            <div class="input-group">
              <input type="number" class="form-control" name="score" placeholder="" value="{$obj.score|htmlspecialchars}" id="score" class="">
              <div class="input-group-addon">мин</div>
            </div>
        </div>
      </div>
      <div class="col col-md-3">
        
        <div class="form-group">
          
        </div>
      </div>
    </div>




    <div class="form-group">
      {include file='includes/frm-panel.tpl' save='Сохранить'}
    </div>
  </div>
</form>

{/block}