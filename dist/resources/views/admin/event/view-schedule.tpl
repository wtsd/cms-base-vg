{extends file="index.tpl"}

{block name="content-wrapper"}
{if ($obj.timestamp|strtotime < $smarty.now)}
{assign var="header_text" value="Прошедшая бронь"}
{assign var="header_class" value="warning"}
{else}
{assign var="header_text" value="Активная бронь"}
{assign var="header_class" value="success"}
{/if}
{if $obj.status == 0}
{assign var="header_text" value="Отменённая бронь"}
{assign var="header_class" value="danger"}
{/if}

<div class="panel panel-{$header_class}">
    <div class="panel-heading">
        {$header_text} [#{$obj.id}]
    </div>
    <div class="panel-body">
            
        <table class="table table-striped">
        <tbody>
            <tr>
                <td>Дата мероприятия</td>
                <td>
                    <time datetime="{$obj.timestamp}" title="{$obj.timestamp}">
                    <i class="fa fa-calendar"></i>
                    {$obj.timestamp|date_format:'%A, %e %b, %H:%M'}
                    </time>
                </td>
            </tr>
            <tr>
                <td>Стоимость</td>
                <td>
                    <i class="fa fa-money"></i>
                    {$obj.price} руб. {if $obj.price != $obj.original_price}<strike>{$obj.original_price} руб.</strike>{/if}
                    ({$obj.participants} чел.)
                </td>
            </tr>
            <tr>
                <td>Квест</td>
                <td>{$obj.event_name}</td>
            </tr>
            {if $obj.name != ''
                || $obj.email != ''
                || $obj.tel != ''}
            <tr>
                <td>Контакт</td>
                <td>
                    {if $obj.name != ''}
                        <i class="fa fa-user"></i>
                        {$obj.name} 
                    {/if}
                    {if $obj.email != ''}
                        <br>
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:{$obj.email|htmlspecialchars}">{$obj.email}</a>
                    {/if} 
                    {if $obj.tel != ''}
                        <br>
                        <i class="fa fa-mobile"></i>
                        {$obj.tel}
                    {/if}
                </td>
            </tr>
            {/if}
            <tr>
                <td>Комментарий</td>
                <td>{$obj.comment|nl2br}</td>
            </tr>
            <tr>
                <td>Заявка</td>
                <td>
                    {$obj.cdate}
                    {if $obj.ip != ''}({$obj.ip})
                    {/if}
                </td>
            </tr>
            
            {if ($obj.timestamp|strtotime < $smarty.now) && $obj.winner != ''}
            <tr>
                <td>Победитель</td>
                <td>
                    {$obj.winner} ({$obj.score})
                </td>
            </tr>
            {/if}
        </tbody>    
        </table>
        <div class="btn-group" role="group" aria-label="...">
          <a href="/{$prefix}/schedule/edit/{$obj.id}" class="btn btn-primary" >
            <i class="fa fa-pencil"></i> Редактировать
          </a>
          <a href="/{$prefix}/schedule/browse/" class="btn btn-default" >
            <i class="fa fa-list"></i> Список броней
          </a>
          <a href="/{$prefix}/schedule/copy/{$obj.id}" class="btn btn-default" >
            <i class="fa fa-copy"></i> Копировать
          </a>
          <a href="/{$prefix}/schedule/status/{$obj.id}" class="btn btn-default" >
            {if $obj.status == 1}<i class="fa fa-eye-slash"></i> Отменить{else}<i class="fa fa-eye"></i> Включить{/if}
          </a>
          <button data-href="/{$prefix}/schedule/delete/{$obj.id}" class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete">
            <i class="fa fa-trash"></i> Удалить
          </button>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Удаление
            </div>
            <div class="modal-body">
                Вы действительо хотите удалить бронь?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <a class="btn btn-danger btn-ok">Удалить</a>
            </div>
        </div>
    </div>
</div>
{/block}