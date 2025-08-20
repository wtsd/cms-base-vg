{extends file="index.tpl"}

{block name="title"}Заказ оформлен! {$page_title}{/block}

{block name="content-breadcrumb"}
<div class="row">
  <ul class="breadcrumb">
    <li><a href="/">{$labels.mainpage}</a></li>
    <li><a href="/cart">{$labels.cart.title}</a></li>
    <li>{$labels.cart.orderformtitle}</li>
    <li class="active">{$labels.cart.ordercheckedout}</li>
  </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row normal-block">

      {if $result == 1}
          <h3>
          Заказ оформлен и сохранён!
              
          </h3>
          <p>Стоимость товаров: {$sum}</p>
          <p>Стоимость доставки: {$deliveryCost}</p>
          {if $payment_type != 'online'}
            <p>Если вы хотите оплатить прямо сейчас, перейдите на сайт оплаты.</p>
          {/if}
          {if $environment == 'DEV'}
          <p>
          Теперь Вы будете перенаправлены на сайт платёжной системы РОБОКАССА. После оплаты Вы будете возвращены на наш сайт.
          </p>
          <form action="https://merchant.roboxchange.com/Index.aspx" method="post">
                <input type="hidden" name="MrchLogin" value="{$details.mrh_login}"">
                <input type="hidden" name="OutSum" value="{$details.out_summ}">
                <input type="hidden" name="InvId" value="{$details.inv_id}">
                <input type="hidden" name="Desc" value="{$details.inv_desc}">
                <input type="hidden" name="SignatureValue" value="{$details.crc}">
                <input type="hidden" name="Shp_item" value="{$details.shp_item}">
                <input type="hidden" name="IncCurrLabel" value="{$details.in_curr}">
                <input type="hidden" name="Culture" value="{$details.culture}">
                <input type="hidden" name="IsTest" value="1">
                <button class="btn btn-primary">Перейти на сайт оплаты</button>
                
          </form>
        {/if}
      {else}
          <h3>Произошла ошибка во время оформления заказа</h3>
          <p>{$message}</p>
          <p><a href="javascript:window.history.back();">&larr; Вернуться</a></p>
      {/if}
</div>
{/block} 