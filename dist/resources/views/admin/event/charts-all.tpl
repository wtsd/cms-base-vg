{extends file="index.tpl"}

{block name="title"}Графики{/block}


{block name="content-wrapper"}{strip}
<h2>Графики</h2>
<div class="row">
    <form action="/{$prefix}/charts/" method="get" name="frm_filter">
        <div class="col col-md-3">
            <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon datepicker-icon" title="Дата проведения"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control datepicker" name="fromDate"  value="2016-05-03">
                </div>
            </div>

        </div>
        <div class="col col-md-3">
            <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon datepicker-icon" title="Дата проведения"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control datepicker" name="toDate"  value="2016-06-03">
                </div>
            </div>
        </div>
        <div class="col col-md-3">
            <button class="btn btn-primary">
                <i class="fa fa-filter"></i> Фильтровать
            </button>
        </div>
    </form>
</div>
<div class="row">
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
</div>
{/strip}{/block}