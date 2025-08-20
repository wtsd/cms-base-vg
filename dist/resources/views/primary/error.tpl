{extends file="index.tpl"}

{block name="meta"} {/block}

{block name="title"}Ошибка!{/block}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i></a></li>

        <li class="active">Ошибка!</li>
    </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="container">
    <div class="error-description normal-block">
    {if $type == 'db'}
        <h2>Ошибка базы данных</h2>
        <p>{$error|nl2br}</p>
    {elseif $type == 'production'}
        <h2>Произошла внутренняя ошибка!</h2>
        <p>Наш технический персонал уведомлён о происшествии.</p>
    {else}
        <h2>Ошибка!</h2>
        <p>{$error|nl2br}</p>
        <p><a href="/">Попробуйте зайти с главной страницы, пожалуйста!</a></p>
    {/if}
        <p class="disclaimer">Информация об ошибке записана. Администрация сайта уже занимается проблемой.</p>
    </div>
</div>
{/block}
