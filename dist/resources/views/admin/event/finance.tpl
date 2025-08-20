{extends file="index.tpl"}

{block name="title"}Финансы{/block}


{block name="content-wrapper"}{strip}
<h2>Финансы</h2>
<div class="row">
    <form action="/{$prefix}/finance/" method="get" name="frm_filter">
        <div class="col col-md-3">
            <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon datepicker-icon" title="Дата проведения"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control datepicker" name="from"  value="{if $from == ''}{"-1 month"|date_format:'Y-m-d'}{else}{$from}{/if}">
                </div>
            </div>

        </div>
        <div class="col col-md-3">
            <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon datepicker-icon" title="Дата проведения"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control datepicker" name="to"  value="{if $to == ''}{$smarty.now|date_format:'Y-m-d'}{else}{$to}{/if}">
                </div>
            </div>
        </div>
        <div class="col col-md-3">
            <select name="event_id" id="" class="form-control">
                <option value="0">-- Все квесты --</option>
                {foreach from=$events item=row}
                <option value="{$row.id}"{if $row.id == $event_id} selected="selected"{/if}>{$row.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="col col-md-3">
            <button class="btn btn-primary">
                <i class="fa fa-filter"></i> Фильтровать
            </button>
            <a href="/{$prefix}/finance/" class="btn btn-danger">
                <i class="fa fa-remove"></i>
            </a>
        </div>
    </form>
</div>
<div class="row">
    {if $to != ''}
    <div class="panel panel-default">
        <div class="panel-body">
            <p>Период: {$from|date_format:'Y-m-d'} —  {$to|date_format:'Y-m-d'}</p>
            <p>
                Общая сумма: <strong>
                {$data|number_format:0:'.':' '} р
                </strong>
            </p>
        </div>
    </div>
    {/if}
</div>
{/strip}{/block}