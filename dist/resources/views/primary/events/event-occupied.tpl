{extends file="index.tpl"}

{*block name="title"}{$page_title}{/block*}

{block name="content-wrapper"}
<div class="event-occupied normal-block">
    <h2>К сожалению, на это время уже была забронирована игра</h2>
    <p>Посмотрите, пожалуйст, другое удобное для вас время на <a href="/event/{$event.rewrite}/">странице расписания</a>.</p>
    <p>Пока вы можете почитать про <a href="/events/">наши квесты</a>.</p>
    
</div>
{/block}