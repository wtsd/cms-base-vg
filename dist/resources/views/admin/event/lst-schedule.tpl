{extends file="index.tpl"}

{block name="content-wrapper"}

<div class="pull-right">
    
    {assign var="preUrl" value='/adm/schedule/browse/%d/?q='|cat:$q|cat:'&date='|cat:$date}
    {include file='paginator.tpl' preUrlSprint=true}
    {*if isset($q)}
    {else}
    {include file='paginator.tpl'}
    {/if*}
</div>
<h2>Бронь <a href="/{$prefix}/schedule/add/"><i class="fa fa-plus-square"></i></a></h2>


<div class="filters">
    <div class="search pull-right">
        <form action="/adm/schedule/browse/" method="get" name="frm_search" class="form-inline">
            <div class="form-group">
                <label class="sr-only" for="q">Поиск:</label>
                <input type="text" name="q" value="{if isset($q)}{$q|htmlspecialchars}{/if}" placeholder="Поиск" class="form-control">
                <button type="submit" class="btn btn-default">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    <ul class="nav nav-pills">
        <li{if $date == 'today'} class="active"{/if}><a href="/{$prefix}/{$ctype}/?{if $event_id > 0}event_id={$event_id}&amp;{/if}date=today">Сегодня</a></li>
        <li{if $date == 'tomorrow'} class="active"{/if}><a href="/{$prefix}/{$ctype}/?{if $event_id > 0}event_id={$event_id}&amp;{/if}date=tomorrow">Завтра</a></li>
        <li{if $date == 'week'} class="active"{/if}><a href="/{$prefix}/{$ctype}/?{if $event_id > 0}event_id={$event_id}&amp;{/if}date=week">В ближайшие 7 дней</a></li>
        <li{if $date == ''} class="active"{/if}><a href="/{$prefix}/{$ctype}/{if $event_id > 0}?event_id={$event_id}{/if}">За всё время</a></li>
        
    </ul>
</div>

<form name="frm_lst" method="post" data-model="event\Schedule">
<table class="table table-striped table-hover">
    {*
    <thead>
     <tr>
        <th>id</th>
        <th>Время</th>
        <th>Гость</th>
        <th>Комментарий</th>
        <th></th>
        <th></th>
     </tr>
    </thead>
    *}
<tbody>
 {assign var="scheduleStat" value='new'}
 {foreach from=$records item=record}
 {if ($record.timestamp|strtotime < $smarty.now) && $scheduleStat == 'new'}
    {assign var="scheduleStat" value='old'}
    <tr class="danger">
        <td colspan="8">-- сейчас {$smarty.now|date_format:'H:i, d-m-Y'} --</td>
    </tr>
 {/if}
 <tr class="{if $scheduleStat == 'new'} warning{/if}{if $record.status == 2} danger{/if}{if $record.status == 0} transparent{/if}">
    <td>
        <a href="/{$prefix}/{$ctype}/view/{$record.id}/" class="btn btn-xs btn-success">
            <i class="fa fa-eye"></i>
        </a>
    </td>
    <td>
        <a href="/{$prefix}/{$ctype}/edit/{$record.id}/" title="{$record.timestamp}">
        <nobr>
            <time datetime="{$record.timestamp}">
                <i class="fa fa-calendar"></i> 
                {$record.timestamp|date_format:'%a %e %b %H:%M'}
            </time>
        </nobr>
        </a>
        {if $record.event_name}
        <br>
        <small class="text-muted">{$record.event_name}</small>
        {/if}
    </td>
    <td>
        {if $record.email != ''}
        <a href="mailto:{$record.email}" title=""><i class="fa fa-envelope-o"></i></a>
        {/if}
        {if $record.name != ''}
        {$record.name}
        {/if} 
        {if $record.tel != ''}
        <br>
        <i class="fa fa-mobile"></i>
            {$record.tel}
        {/if}
        &nbsp;
    </td>
    <td>
        {if $record.comment != ''}
        <small>
        <i class="fa fa-comment-o"></i> 
        {$record.comment|nl2br}
        </small>
        {else}
        &nbsp;
        {/if}
    </td>
    <td>
        <nobr>
        {$record.participants} чел. /
        <em>
            <span title="Цена по умолчанию">{$record.price} р.</span>
            {if $record.price != $record.event_price}
            <small title="Финальная цена">({$record.event_price}&nbsp;р.)</small>
            {/if}
        </em>
        </nobr>
    </td>
    
    <td>

        <a href="/{$prefix}/{$ctype}/delete/{$record.id}/" class="delete btn btn-danger btn-xs" data-id="{$record.id}">
            <i class="fa fa-trash-o"></i>
        </a>

        <a href="/{$prefix}/{$ctype}/edit/{$record.id}/" class="btn btn-warning btn-xs">
            <i class="fa fa-pencil"></i>
        </a>
    </td>
 </tr>
 {/foreach}
</tbody>
</table>
</form>
{assign var="preUrl" value='/adm/schedule/browse/%d/?q='|cat:$q|cat:'&date='|cat:$date}
{include file='paginator.tpl' preUrlSprint=true}

<div class="row">
    <a href="/{$prefix}/{$ctype}/add/" class="btn btn-primary">Новая бронь</a>
    <p>Мероприятие отображается на сайте в виде страницы, на которой можно оставить заявку.</p>
</div>

{*
    <h4><span class="glyphicon glyphicon-stats"></span> Статистика</h4>
    <table class="table">
        <thead>
        <tr>
            <th colspan="2">Бронь</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Текущий месяц</td>
            <td>{$stat.month}{if $stat.month > $stat.prevmonth} <span class="glyphicon glyphicon-arrow-up text-success"></span>{/if}</td>
        </tr>

        <tr>
            <td>Прошлый месяц</td>
            <td>{$stat.prevmonth}</td>
        </tr>

        <tr>
            <td>Всего</td>
            <td>{$stat.all}</td>
        </tr>

        <tr>
            <td>Ожидается</td>
            <td>{$stat.planned}</td>
        </tr>
        </tbody>
        <thead>
        <tr>
            <th colspan="2">Доход (руб)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Текущий месяц</td>
            <td>{$stat.moneymonth|number_format:0:',':'&nbsp;'}</td>
        </tr>
        <tr>
            <td>Прошлый месяц</td>
            <td>{$stat.moneyprevmonth|number_format:0:',':'&nbsp;'}</td>
        </tr>
        <tr>
            <td>Всего</td>
            <td>{$stat.moneyall|number_format:0:',':'&nbsp;'}</td>
        </tr>
        <tr>
            <td>Ожидается за месяц</td>
            <td>{$stat.moneyplannedbeom|number_format:0:',':'&nbsp;'}</td>
        </tr>
        <tr>
            <td>Ожидается</td>
            <td>{$stat.moneyplanned|number_format:0:',':'&nbsp;'}</td>
        </tr>
        </tbody>
    </table>
    *}

{/block}
