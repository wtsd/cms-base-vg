{strip}
<div class="offer-item" data-id="{$offer.id}">
    <h4 class="name">
      <a href="/offer/{if $offer.rewrite != ''}{$offer.rewrite}{else}{$offer.id}{/if}/" title="{$offer.name|htmlspecialchars}">
      {$offer.name}
      </a>
    </h4>
    <a href="/offer/{$offer.rewrite}/" class="offer-thumb">
        {if $offer.photo != ''}
      <img data-src="/img/offer/{$offer.id}/thumb/{$offer.photo}" src="/img/offer/{$offer.id}/thumb/{$offer.photo}" alt="{$offer.name|htmlspecialchars}">
      {else}
      <img data-src="/img/nopreview.jpg" src="/img/nopreview.jpg" alt="">
      {/if}
      <span class="over"><i class="fa fa-search-plus"></i></span>
    </a>
    {if $offer.price > 0}
    <div class="price">{$offer.price_label}</div>
    <div class="btn-cart">
        <div class="msg"></div>
        <a href="#" class="addToCart btn btn-default btn-sm" data-offer-id="{$offer.id}"><i class="fa fa-shopping-cart"></i> Добавить в корзину</a>
    </div>
    {else}
    <div class="price">Нет в наличии.</div>
    {/if}
</div>
{/strip}