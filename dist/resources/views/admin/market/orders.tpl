{extends file="index.tpl"}

{block name="content-wrapper"}
<p class="pull-right">Всего заказов: {$cnt}</p>
<h2>Заказы</h2>
<table class="orders-list table table-condensed">
{foreach from=$records item=record}
    <tr>
        <td width="400">
            <a name="{$record.id}"></a>
            <h4 title="IP: {$record.ip}">
                <i class="fa fa-user" aria-hidden="true"></i> {$record.name} {$record.lastname} 
                <sup>
                    
                    {if $record.status == 'unpaid'}
                    <span class="label label-default">
                    Не оплачен
                    </span>
                    {elseif $record.status == 'paid'}
                    <span class="label label-danger">
                    Оплачен
                    </span>
                    {elseif $record.status == 'in_progress'}
                    <span class="label label-warning">
                    Обрабатывается
                    </span>
                    {elseif $record.status == 'done'}
                    <span class="label label-info">
                    Выполнен
                    </span>
                    {/if}
                </sup>
            </h4>
        </td>
        <td>
            <p class="pull-right">
                <i class="fa fa-calendar"></i> {$record.cdate}
            </p>
            <a href="/adm/orders/status/{$record.id}/deleted/" class="btn btn-sm btn-danger">
                <i class="fa fa-trash"></i>
            </a>
            {if $record.status|in_array:['unpaid','paid']}
            <a href="/adm/orders/status/{$record.id}/in_progress/" class="btn btn-sm btn-info">
                Принять
            </a>
            {/if}
            {if $record.status == 'in_progress'}
            <a href="/adm/orders/status/{$record.id}/done/" class="btn btn-sm btn-primary">
                Завершить
            </a>
            {/if}


              <a href="#" data-toggle="modal" data-target="#comment-modal-{$record.id}" class="btn btn-sm {if $record.int_comment != ''}btn-primary{else}btn-warning{/if}">&nbsp;<i class="fa {if $record.int_comment != ''}fa-comment{else}fa-comment-o{/if}" title="Скрытый комментарий"></i>&nbsp;</a>

              {include file="market/orders-modal-comment.tpl"}


            {*
            <a href="/adm/orders/status/{$record.id}/cancel/" class="btn btn-sm btn-default">
                Отменить
            </a>
            *}
        </td>
    </tr>
    <tr>
        <td>
            <i class="fa fa-envelope-o"></i> <a href="mailto:{$record.email}">{$record.email}</a><br>
            <i class="fa fa-whatsapp" aria-hidden="true"></i> {$record.phone}<br>
            Адрес: 
            <a href="https://www.google.com/maps/place/{$record.address|urlencode}+{$record.city|urlencode}" target="_blank">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
            г. {$record.city},
                    {$record.address}
            </a>
            <br><br>
            {if $record.comment != ''}
            <p>
                <i class="fa fa-comment-o"></i> Комментарий к заказу
            </p>
            <p class="text-warning">
                
                {$record.comment|htmlspecialchars|nl2br}
            </div>
            
            {/if}
        </td>
        <td>
            <h5>Содержание заказа #{$record.id}</h5>
            <table class="table table-bordered">
                {foreach from=$record.offers item=offer}
                <tr>
                    <td width="50">
                        #{$offer.id}
                    </td>
                    <td width="50">
                        <a href="/offer/{$offer.rewrite}">
                            <img src="/img/offer/{$offer.id}/full/{$offer.fname}" alt="" style="max-height: 50px; max-width: 50px;">
                        </a>
                    </td>
                    <td>
                         <a href="/offer/{$offer.rewrite}" target="_blank">{$offer.name}</a> 
                    </td>
                    <td width="120" style="text-align: right;">
                        {$offer.price}&nbsp;руб. / ед.
                    </td>
                    <td width="50">
                        &times;{$offer.quantity}
                        
                    </td>
                    <td width="100" style="text-align: right;">
                        {$offer.price* $offer.quantity}&nbsp;руб.
                    </td>
                </tr>
                {/foreach}
            </table>
            <p class="pull-right" style="text-align: right;">
                Общая сумма: 
                <strong>
                {$record.sum} руб.
                </strong>
                <br>

                Доставка: 
                {$record.delivery_cost} руб.
                <br>

                Налог: 
                {$record.taxes} руб.
                <br>

                Итого: 
                <strong>
                {$record.sum + $record.taxes + $record.delivery_cost} руб.
                </strong>
            </p>
            {*$record.offers|var_dump*}
            {*$record|var_dump*}

        </td>
   </tr>
{/foreach}
</table>
{include file="includes/pagination.tpl"}
{/block}