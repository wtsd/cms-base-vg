{extends file="index.tpl"}

{block name="title"}Заказ обратного звонка{/block}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/">Главная</a> </li>

        <li class="active">Заказ обратного звонка</li>
    </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row normal-block">
{if $status == 'success'}
    <h2>Звонок заказан!</h2>
    <p>Звонок благополучно заказан. В самое ближайшее время наш оператор свяжется с Вами.</p>
{else}
    <h2>Заказ обратного звонка!</h2>
    {include file="includes/frm-request-callback.tpl"}
{/if}
</div>
{/block}