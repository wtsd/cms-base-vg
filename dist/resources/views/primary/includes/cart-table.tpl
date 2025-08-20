{strip}<div class="table-responsive">
  <table class="cart-offers table table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th></th>
        <th>Наименование</th>
        <th>Количество</th>
        <th>Стоимость</th>
        <th>Цена</th>
        {if !$print}
        <th>Управление</th>
        {/if}
      </tr>
    </thead>
    <tbody>
    {foreach from=$offers item=offer name=cartoffers key=id}
      <tr class="offer_{$id}">
        <td>{$smarty.foreach.cartoffers.index + 1}</td>
        <td>
          <a href="/offer/{$offer.rewrite}/">
            {if count($offer.images) > 0}
            <img data-src="/img/offer/{$offer.id}/thumb/{$offer.images[0].fname}" src="/img/offer/{$offer.id}/thumb/{$offer.images[0].fname}" alt="" class="img-rounded">
            {else}
            <img data-src="/img/nopreview.jpg" src="/img/nopreview.jpg" alt="" class="img-rounded">
            {/if}
          </a>
        </td>
        <td>
          <a href="/offer/{$offer.rewrite}/">
            {$offer.name}
          </a>
          (#{$offer.id})

        </td>
        <td>
          <a href="#" class="quantity-change badge" data-action="sub" data-offer="{$offer.id}">-</a>
          &nbsp;
          <input type="number" min="0" value="{$offer.quantity}" class="quantity" data-offer="{$offer.id}">
          &nbsp;
          <a href="#" class="quantity-change badge" data-action="add" data-offer="{$offer.id}">+</a>
        </td>
        <td>{$offer.price}</td>
        <td class="price-subtot">{*$offer.price*}{$offer.price * $offer.quantity}</td>
        {if !$print}
        <td>
          <button type="button" class="removeFromCart btn btn-danger btn-xs" data-offer="{$id}">Удалить</button> 
          <!--button type="button" class="btn btn-info btn-xs" data-url="/offer/{$offer.rewrite}/">Просмотреть</button-->
        </td>
        {/if}
      </tr>
    {/foreach}
    </tbody>
  </table>
</div>{/strip}