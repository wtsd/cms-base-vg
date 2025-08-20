{extends file="index.tpl"}

{*block name="title"}{$page_title}{/block*}

{block name="content-wrapper"}
<div class="event-schedule normal-block">
    <h1>Расписание — <a href="/event/{$event.rewrite}/">{$event.name}</a></h1>
    <div class="timetable">
        <table>
            <tbody>
                {foreach from=$schedule item=day}
                <tr>
                    <th>{$day.f_name}<br>{$day.date}</th>
                    {foreach from=$day.timetable item=time}
                    <td>
                        {if $time.status != 'occupied'}<a href="/event/{$event.rewrite}/submit/{$time.id}/{$day.date}/" class="do-register" data-time="{$time.id}-{$day.date}">
                            <strong>{$time.time}</strong></a>
                            <br>
                            <em>{$time.price}</em>
                        </a>
                        {else}
                        {$time.time}<br>
                        бронь
                        {/if}
                    </td>
                    {/foreach}
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{/block}