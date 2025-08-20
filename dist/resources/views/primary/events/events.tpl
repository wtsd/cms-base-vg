{extends file="index.tpl"}

{*block name="title"}{$page_title}{/block*}

{block name="content-wrapper"}
<div class="all-events normal-block">
    <h2>Все квесты</h2>
    {foreach from=$events item=event}
    <div class="single-event">
        <div class="img">
            <a href="/event/{$event.rewrite}/"><img src="/img/events/{$event.id}/{$event.fname}" alt="{$event.name}"></a>
        </div>
        <div class="name">
            <a href="/event/{$event.rewrite}/">{$event.name}</a>
        </div>
    </div>
    {/foreach}
</div>
{/block}