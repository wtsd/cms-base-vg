{extends file="print.tpl"}

{block name="title"}Просмотр корзины - {$page_title}{/block}

{block name="content-wrapper"}{strip}

<div class="row normal-block">
  <p>Всего товаров: <strong class="cartCount">{$offers|count}</strong></p>
  <p>На сумму: <strong class="cartSum">{$sum}</strong> рублей</p>
  {if $offers|@count > 0}
    {include file="includes/cart-table.tpl" offers=$offers print=true}
  {/if}
</div>

{/strip}{/block}