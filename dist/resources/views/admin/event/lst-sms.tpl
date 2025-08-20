{extends file="index.tpl"}

{block name="content-wrapper"}
<table class="table table-condensed">
    <thead>
        <tr>
            <th>id</th>
            <th>Бронь</th>
            <th>Дата отправления</th>
            <th>result</th>
        </tr>
    </thead>
{foreach from=$records item=record}
        <tr>
            <td>{$record.id}</td>
            <td><a href="/adm/schedule/edit/{$record.schedule_id}">{$record.schedule_id}</a> ({$record.timestamp} на {$record.event_name})</td>
            <td>{$record.cdate}</td>
            <td>{$record.result}</td>
        </tr>
{/foreach}
</table>
{/block}