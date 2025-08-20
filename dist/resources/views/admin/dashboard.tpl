{extends file="index.tpl"}

{block name="title"}Dashboard — {$labels.title} &mdash; {$labels.ver}{/block}


{block name="content-wrapper"}{strip}
<!-- <h2>Dashboard</h2> -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-pencil-square-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div>Пост</div>
                    </div>
                </div>
            </div>
            <a href="/{$prefix}/article/add/">
                <div class="panel-footer">
                    <span class="pull-left">Написать новый</span>
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
    <div class="col-lg-6 col-md-6">
        <table class="table table-striped">
            <tbody>
                {foreach from=$posts item=post}
                <tr>
                    <td><a href="/article/{$post.rewrite}/" target="_blank">{$post.name}</a></td>
                    <td>{$post.cdate|date_format:'D, F j H:i'}</td>
                    <td></td>
                </tr>
                {/foreach}
                <tr>
                    <th colspan="3">
                        <a href="/{$prefix}/article/browse/" class="btn btn-default btn-xs pull-right">
                            Все посты
                        </a>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
    {*
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-calendar-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{$todaysSchedules}</div>
                        <div>Расписание</div>
                    </div>
                </div>
            </div>
            <a href="/{$prefix}/schedule/browse/">
                <div class="panel-footer">
                    <span class="pull-left">Посмотреть</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
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
            <a href="/{$prefix}/charts/">
                <div class="panel-footer">
                    <span class="pull-left">Подробнее…</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

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

    {foreach from=$data item=info key=name}
    <div class="col col-md-6">
        <div id="{$name}" style="width:100%; height:400px;"></div>
    </div>
    <script>
    $(function () {
        $('#{$name}').highcharts({$info});
        
    });
    </script>
    {/foreach}
    *}
</div>

{/strip}{/block}