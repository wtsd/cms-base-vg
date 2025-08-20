{extends file="index.tpl"}

{block name="title"}Заказ оформлен! {$page_title}{/block}

{block name="content-breadcrumb"}
<div class="row">
  <ul class="breadcrumb">
    <li><a href="/">Главная</a></li>
    <li><a href="/cart">Корзина</a></li>
    <li>Оформление заказа</li>
    <li class="active">Заказ оформлен!</li>
  </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row normal-block">
    {if $result == 1}
        <h3>Ваш заказ оформлен!</h3>
        <p>Ваш заказ благополучно оформлен. В ближайшее время с Вами свяжется наш менеджер для подтверждения заказа.</p>
    {else}
        <h3>Произошла ошибка во время оформления заказа</h3>
        <p>{$message}</p>
        <p><a href="javascript:window.history.back();">&larr; Вернуться</a></p>
    {/if}
</div>
{/block}