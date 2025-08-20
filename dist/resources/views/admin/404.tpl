{extends file="index.tpl"}

{block name="meta"} {/block}

{block name="title"}Ошибка!{/block}


{block name="content-wrapper"}
<div class="container">
    <div class="col-md-6">
        <h2 class="text-danger">Ошибка 404</h2>
        <p>Страница <code>{$url}</code> не найдена</p>
        <p><a href="/{$prefix}/">Попробуйте зайти с главной страницы, пожалуйста!</a></p>
        <p>Информация об ошибке записана. Администрация сайта уже занимается проблемой.</p>
    </div>
</div>
{/block}
