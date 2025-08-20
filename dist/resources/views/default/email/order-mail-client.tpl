Добрый день!

Сегодня на сайте <a href="{$base_url}/">{$site}</a> был сделан заказ с указанием Вашего почтового ящика. Если этот адрес кто-то указал по ошибке, просто проигнорируйте письмо. Если же это сделали Вы, подтвердите, пожалуйста, правильно ли мы сохранили введённые Вами данные:

Имя {$order.name} {$order.lastname}
Телефон {$order.phone}
Адрес г.{$order.city}, {$order.address}
Метод оплаты: {$order.payment}
Общая сумма {$order.sum}

Заказ включает в себя
ТОВАРЫ

<table>
    <thead>
      <tr>
        <th>#</th>
        <th></th>
        <th>Наименование</th>
        <th>Количество</th>
        <th>Стоимость</th>
        <th>Цена</th>
      </tr>
    </thead>
    <tbody>

    {foreach from=$order.offers item=offer name=cartoffers key=id}
      <tr>
        <td>{$smarty.foreach.cartoffers.index + 1}</td>
        <td>
          <a href="{$base_url}/offer/{$offer.rewrite}/">
            {if count($offer.images) > 0}
            <img src="{$base_url}/img/offer/{$offer.id}/thumb/{$offer.images[0].fname}">
            {else}
            <img src="{$base_url}/img/nopreview.jpg">
            {/if}
          </a>
        </td>
        <td>
          <a href="{$base_url}/offer/{$offer.rewrite}/">
            {$offer.name}
          </a>
          (#{$offer.id})

        </td>
        <td>{$offer.quantity}</td>
        <td>{$offer.price} руб.</td>
        <td>{$offer.price * $offer.quantity} руб.</td>
      </tr>
    {/foreach}
    </tbody>
</table>
Общая сумма {$order.sum}



<a href="{$base_url}/cart/order/confirm/?hash={$hash}">[Подтвердить заказ]</a>

Спасибо за то, что воспользовались нашим сайтом! Надеемся, Вам всё понравилось. Если у Вас есть пожелания, критика или предложения, <a href="{$base_url}/feedback/">напишите об этом нам</a>.

Всего Вам доброго!

С уважением,

{$signature}

