{extends file="index.tpl"}

{*block name="title"}{$page_title}{/block*}

{block name="content-wrapper"}
<div class="event-full normal-block">
    <h1>{$event.name}</h1>
    <div class="img">
        <img src="/img/events/{$event.id}/{$event.fname}" alt="{$event.name}">
    </div>
    <div class="descr">
        {$event.descr}
    </div>
    <div class="register">
        <a href="/event/{$event.rewrite}/register/" class="btn-submit">
            Записаться!
        </a>
    </div>
</div>
{/block}