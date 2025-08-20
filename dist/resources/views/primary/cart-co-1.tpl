{extends file="index.tpl"}

{block name="title"}Корзина! {$page_title}{/block}

{block name="content-wrapper"}
    <ul class="breadcrumb">
      <li><a href="/">Главная</a></li>
      <li><a href="/cart">Корзина</a></li>
      <li class="active">Оформление заказа</li>
    </ul>
  <div class="cart-fixed">
    {if $offers|@count > 0}
      <table class="cart-offers table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th></th>
            <th>Наименование</th>
            <th>Количество</th>
            <th>Цена</th>
          </tr>
        </thead>
        <tbody>
        {foreach from=$offers item=offer name=cartoffers key=id}
          <tr class="offer_{$id}">
            <td>{$smarty.foreach.cartoffers.index + 1}</td>
            <td>
              <a href="/offer/{$offer.rewrite}/">
                <img data-src="/img/offer/{$offer.id}/thumb/{$offer.images[0].fname}" src="/img/offer/{$offer.id}/thumb/{$offer.images[0].fname}" alt="" class="img-rounded">
              </a>
            </td>
            <td>
              <a href="/offer/{$offer.rewrite}/">
                {$offer.name|trim}
              </a>
              (#{$offer.id})

            </td>
            <td>1</td>
            <td>{if isset($offer.price_label)}{$offer.price_label}{else}нет в наличии{/if}</td>
          </tr>
        {/foreach}
        <tr>
            <td colspan="3"></td>
            <th>Итого:</th>
            <td><strong class="cartSum">{$sum}</strong> рублей</td>
        </tr>
        </tbody>
      </table>

    </div>
    {else}
    <p>Корзина пуста, оформление заказа невозможно! Добавьте для начала что-нибудь из <a href="/products">каталога</a> в корзину.</p>
    {/if}
  </div>

{/block}