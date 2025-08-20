<table>
    <tr>
        <td colspan="2">
            ID: {$record.id} | {$record.cdate} | {$record.ip}
        </td>
    </tr>
    <tr>
        <td>
            Клиент: {$record.name} {$record.lastname} 
            <a href="mailto:{$record.email}">{$record.email}</a><br>
            Телефон: {$record.phone}<br>
            Адрес: г. {$record.city},
                {$record.address}
                <br>
            Метод оплаты: {$record.payment_type}<br>
            Общая сумма: {$record.sum}<br>
        </td>
        <td>
            <h5>Содержание заказа</h5>
            <ul>
                {foreach from=$offers item=offer}
                <li>#{$offer.id} <a href="/offer/{$offer.rewrite}" target="_blank">{$offer.name}</a> {$offer.price} — {$offer.quantity} шт.</li>
                {/foreach}
            </ul>
        </td>
   </tr>
</table>

<p>Отвечать на это письмо не нужно, так как я всего лишь робот.</p>
<p>Хорошего дня!</p>