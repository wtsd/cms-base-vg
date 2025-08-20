{extends file="index.tpl"}

{block name="title"}Dashboard — {$labels.title} &mdash; {$labels.ver}{/block}


{block name="content-wrapper"}
{*<h2>Dashboard</h2>*}
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <a href="/{$prefix}/offer/browse/" style="color:#fff;">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-calendar-plus-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">Товары</div>
                        <div>
                        Всего: {$stat.offers.cnt}
                        </div>
                    </div>
                </div>
            </div>
            </a>
            <a href="/{$prefix}/offer/add/">
                <div class="panel-footer">
                    <span class="pull-left">Создать новый</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <a href="/{$prefix}/orders/browse/">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">Заказы</div>
                        <div>
                        Кол-во: {$stat.total.cnt}
                        <br>
                        Сумма: {$stat.total.sum|number_format:2:".":" "} р.
                        </div>
                    </div>
                </div>
            </div>
                <div class="panel-footer">
                    <span class="pull-left">Посмотреть</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <a href="/{$prefix}/#">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-bar-chart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div>Графики</div>
                    </div>
                </div>
            </div>
                <div class="panel-footer">
                    <span class="pull-left">Подробнее…</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">

        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-clock-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{$smarty.now|date_format:"H:i"}</div>
                        <div>Сегодня</div>
                    </div>
                </div>
            </div>

            <!-- /.panel-body -->

                <div class="panel-footer">
                    <span class="pull-left">{$smarty.now|date_format:"F, d"}</span>
                    <div class="clearfix"></div>
                </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Данные по месяцам
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            
                            <th>Выручка (руб.)</th>
                            <th>Месяц</th>
                            <th>Кол-во заказов</th>
                        </tr>
                    </thead>
                    <tbody>
                    {foreach from=$stat.orders item=record}
                        <tr>
                            <td>{$record.sum}</td>
                            <td>{$record.date}</td>
                            <td>{$record.cnt}</td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
            <!-- /.panel-body -->
        </div>


    </div>
    <div class="col-md-6">
     <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-shopping-cart"></i> Последний заказ
                <p class="pull-right">
                    
                {if $lastOrder.status == 'unpaid'}
                <span class="label label-default">
                Неоплачен
                </span>
                {elseif $lastOrder.status == 'paid'}
                <span class="label label-danger">
                Оплачен
                </span>
                {elseif $lastOrder.status == 'in_progress'}
                <span class="label label-warning">
                Обрабатывается
                </span>
                {elseif $lastOrder.status == 'done'}
                <span class="label label-info">
                Выполнен
                </span>
                {/if}
                </p>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="row">
                <div class="col-md-12">
                    <h4>Контактная информация</h4>
                </div>
                <div class="col-md-5 col-md-offset-7">
                    <i class="fa fa-calendar-o"></i> &nbsp; {$lastOrder.cdate}
                </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <i class="fa fa-user" aria-hidden="true"></i> {$lastOrder.name} {$lastOrder.lastname} 
                    </div>
                    <div class="col-md-4">
                        <a href="mailto:{$lastOrder.email}">{$lastOrder.email}</a>
                    </div>
                    <div class="col-md-4">
                        <i class="fa fa-mobile" aria-hidden="true"></i> {$lastOrder.phone}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        Адрес: 
                        <a href="https://www.google.com/maps/place/{$lastOrder.address|urlencode}+{$lastOrder.city|urlencode}" target="_blank">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        г. {$lastOrder.city},
                                {$lastOrder.address}
                        </a>        
                    </div>
                    <div class="col-md-6">
                        {if $lastOrder.comment != ''}
                        Комментарий:
                        {$lastOrder.comment|htmlspecialchars|nl2br}
                        {/if}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-md-offset-8">
                        <strong>Сумма: {$lastOrder.sum}</strong>
                    </div>
                </div>  
                
                <a href="/adm/orders/browse/" class="btn btn-primary">Перейти к заказу</a>
                
            </div>
        </div>
    </div>
</div>
{*
<div class="row">
    <div class="col col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Данные за месяц
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div id="gMonth" style="width:100%; height:400px;"></div>
            </div>
            <!-- /.panel-body -->
        </div>
                    
                    
    </div>

    <div class="col col-md-6">
        <div class="row">
            <div class="col col-md-6">
                <div id="gToday" style="height:200px;"></div>
            </div>
            <div class="col col-md-6">
                <table class="table">
                    <thead>
                    <tr>
                        <th colspan="2">Бронь</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Текущий месяц</td>
                        <td>{$stat.month}{if $stat.month > $stat.prevmonth} <span class="glyphicon glyphicon-arrow-up text-success"></span>{/if}</td>
                    </tr>

                    <tr>
                        <td>Прошлый месяц</td>
                        <td>{$stat.prevmonth}</td>
                    </tr>

                    <tr>
                        <td>Всего</td>
                        <td>{$stat.all}</td>
                    </tr>

                    <tr>
                        <td>Ожидается</td>
                        <td>{$stat.planned}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <h3>Расписание на сегодня</h3>
        <table class="table table-striped table-hover">
    <thead>
 <tr>
    <th>id</th>
    <th>Время</th>
    <th>Клиент</th>
    <th></th>
 </tr>
</thead>
<tbody>
    {foreach from=$bookings item=record}
    <tr{if $record.status == 0} style="opacity:.7;"{/if}>
    <td><a href="/{$prefix}/schedule/edit/{$record.id}/">{$record.id}</a></td>
    <td>
        <a href="/{$prefix}/schedule/edit/{$record.id}/">
        <nobr>{$record.date}&nbsp;{$record.time}</nobr>
        </a>
        <br>
        <small>{if $record.event_name}{$record.event_name}{else}<em>неизвестный квест</em>{/if} ({$record.event_id})</small>
    </td>
    <td>
        <a href="mailto:{$record.email}" title=""><i class="fa fa-envelope-o"></i></a> 
        {$record.name} 
        <br>
        <small>{$record.tel} </small>
    </td>
    <td>
        
         {$record.participants} чел. <br> {$record.event_price}&nbsp;р.
    </td>
    
 </tr>
    {/foreach}
</tbody>
</table>

    <a href="/{$prefix}/schedule/browse" class="btn btn-default">Посмотреть все брони</a>

    </div>

</div>

<div class="row">
    <div class="col col-md-4">
        
    <h4><span class="glyphicon glyphicon-stats"></span> Финансы</h4>
    <table class="table">
        <tbody>
        <tr>
            <td>Текущий месяц</td>
            <td>{$stat.moneymonth|number_format:0:',':'&nbsp;'} р.</td>
        </tr>
        <tr>
            <td>Прошлый месяц</td>
            <td>{$stat.moneyprevmonth|number_format:0:',':'&nbsp;'} р.</td>
        </tr>
        <tr>
            <td>Всего</td>
            <td>{$stat.moneyall|number_format:0:',':'&nbsp;'} р.</td>
        </tr>
        <tr>
            <td>Ожидается за месяц</td>
            <td>{$stat.moneyplannedbeom|number_format:0:',':'&nbsp;'} р.</td>
        </tr>
        <tr>
            <td>Ожидается</td>
            <td>{$stat.moneyplanned|number_format:0:',':'&nbsp;'} р.</td>
        </tr>
        </tbody>
    </table>

    </div>
    <div class="col col-md-8">
        
        <div class="row">
            <div class="col col-md-12">
                <div id="gYear" style="width:100%; height:500px;"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">    
    <div class="col col-md-12">
        <div id="gOverall"></div>
    </div>

    <script src="/resources/assets/raphael-master/raphael-min.js"></script>
    <script src="/resources/assets/Morris-Good-looking-Charts-Plugin/morris.min.js"></script>
    <link rel="stylesheet" href="/resources/assets/Morris-Good-looking-Charts-Plugin/morris.css">

    <script>
    $(function () {
        $('#gMonth').highcharts({$monthData});
        $('#gYear').highcharts({$yearData});
        
    });
    {literal}

    Morris.Donut({
      element: 'gToday',
      data: [
        {value: {/literal}{$todaysLeft}{literal}, label: 'Сегодня придут'},
        {value: {/literal}{$todaysSchedules - $todaysLeft}{literal}, label: 'Сегодня приходили'},
      ],
      formatter: function (x) { return x }
    });
/*
Morris.Bar({
  element: 'gOverall',
  data: [
    {x: '2011 Q1', y: 3, z: 2, a: 3},
    {x: '2011 Q2', y: 2, z: null, a: 1},
    {x: '2011 Q3', y: 0, z: 2, a: 4},
    {x: '2011 Q1', y: 3, z: 2, a: 3},
    {x: '2011 Q2', y: 2, z: null, a: 1},
    {x: '2011 Q3', y: 0, z: 2, a: 4},
    {x: '2011 Q1', y: 3, z: 2, a: 3},
    {x: '2011 Q2', y: 2, z: null, a: 1},
    {x: '2011 Q3', y: 0, z: 2, a: 4},
    {x: '2011 Q4', y: 2, z: 4, a: 3}
  ],
  xkey: 'x',
  ykeys: ['y', 'z', 'a'],
  labels: ['Y', 'Z', 'A']
});
*/
    {/literal}
    </script>

</div>
*}
{/block}