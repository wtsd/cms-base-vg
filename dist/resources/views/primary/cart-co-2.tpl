{extends file="index.tpl"}

{block name="title"}Корзина!{$page_title}{/block}

{block name="content-wrapper"}
  <ul class="breadcrumb">
    <li><a href="/">Главная</a></li>
    <li><a href="/cart">Корзина</a></li>
    <li>Оформление заказа</li>
    <li class="active">Заказ оформлен!</li>
  </ul>
  
</ul>

<div class="wrapper-fixed order-finished">
    {if $result == 1}
    <h3>Ваш заказ оформлен!</h3>
    <p>Ваш заказ благополучно оформлен. В ближайшее время с Вами свяжется наш менеджер для подтверждения заказа.</p>
    {else}
    <h3>Произошла ошибка во время оформления заказа</h3>
        <p>{$message}</p>
        <p><a href="javascript:window.history.back();">&larr; Вернуться</a></p>
    {/if}
  </div>
</div>
{/block}