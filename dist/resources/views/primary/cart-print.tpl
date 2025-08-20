{extends file="index.tpl"}

{block name="title"}{$page_title}{/block}

{block name="content-wrapper"}
<div class="wrapper-fixed">
    <ul class="breadcrumb">
      <li class="active">Корзина</li>
      <li>Печать</li>
    </ul>
  <div class="cart-fixed">
<p>Всего товаров: <strong class="cartCount">{$offers|count}</strong></p>
<p>На сумму: <strong class="cartSum">{$sum}</strong> рублей</p>
{if $offers|@count > 0}
<table class="table cart-offers">
  <thead>
  <tr>
    <th>№</th>
    <th>Изображение</th>
    <th>Наименование</th>
    <th>Количество</th>
    <th>Цена</th>
    <th>Управление</th>
  </tr>
  </thead>
  <tbody>
  {foreach from=$offers item=offer name=cartoffers key=id}
    <tr class="offer_{$id}">
      <td>{$smarty.foreach.cartoffers.index + 1} {$offer->rewrite}</td>
      <td>
        <a href="/offer/{if $offer->rewrite != ''}{$offer->rewrite}{else}{$offer->id}{/if}/" class="thumbnail">
          <img data-src="/thumb/?size=midthumb&type=offer&id={$offer->id}&subid=0" src="/thumb/?size=thumb&type=offer&id={$offer->id}&subid=0" alt="">
        </a>
      </td>
      <td>
        <a href="/offer/{if $offer->rewrite != ''}{$offer->rewrite}{else}{$offer->id}{/if}/">
          {$offer->name}
        </a>
        (#{$offer->id})

      </td>
      <td>1</td>
      <td>{$offer->price}</td>
      <td><a href="#" class="removeFromCart" data-offer="{$id}">[x]</a></td>
    </tr>
  {/foreach}
  </tbody>
</table>
<button class="clearCart">Очистить</button> <button class="printCart">Распечатать</button>  <button class="checkOutCart"><strong>Оформить заказ!</strong></button>
{/if}
<hr />
<small>После оформление заказа корпусной мебели с Вами свяжется наш оператор в течение часа.</small>
</div>
</div>

{/block}