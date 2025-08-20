{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Бронь <a href="/{$prefix}/schedule/add/"><i class="fa fa-plus-square"></i></a></h2>



<div class="filters">
    <div class="search pull-right">
        <form action="/adm/schedule/browse/" method="get" name="frm_search" class="form-inline">
            <div class="form-group">
                <label class="sr-only" for="q">Поиск:</label>
                <input type="text" name="q" value="{if isset($q)}{$q|htmlspecialchars}{/if}" placeholder="Поиск" class="form-control">
                <button type="submit" class="btn btn-default">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col col-md-6">
        {$dow = strftime('%u', strtotime('first day of this month'))}
        {$lastDay = strftime('%d', strtotime('last day of this month'))}

        <table class="table"> 
            <tr colspan="7">
                <h2>
                    {strftime('%B')}
                </h2>
            </tr>
            <tr>
                <td>Пн</td>
                <td>Вт</td>
                <td>Ср</td>
                <td>Чт</td>
                <td>Пт</td>
                <td><span class="text-danger">Сб</span></td>
                <td><span class="text-danger">Вс</span></td>
            </tr>


            <tr> 
                {* Previous month's days for the first week *}
                {for $i = 1 to $dow-1}
                <td></td>
                {/for}

                {for $i = 1 to $lastDay} 
                    {$nDate = strftime('%Y-%m-%d', mktime(0,0,0, strftime('%m'), $i, strftime('%Y')))}
                <td title="{$nDate}" class="{if $nDate == $smarty.now|date_format:'Y-m-d'} info {/if}" data-time="{$nDate}" data-date="{$nDate|date_format:'%Y-%m-%d'}">
                    {if $nDate == $smarty.now|date_format:'Y-m-d'}<strong>{/if}
                    {if $nDate|date_format:'%u' > 5}<span class="text-danger">{/if}
                    {$i} 
                    {if isset($records)}
                        {if isset($records[$nDate|date_format:'%Y-%m-%d'])}
                        <a href="{$prefix}/schedule/?date={$nDate|date_format:'%Y-%m-%d'}" class="text-muted small">
                            
                        <span class="label label-info small" style="opacity:.6">
                         {$records[$nDate]}
                        </span>
                        </a>
                        {/if}
                    {/if}
                    {if $nDate|date_format:'%u' > 5}</span>{/if}

                    {if $nDate == $smarty.now|date_format:'Y-m-d'}</strong>{/if}
                </td>

                {* Start new row/week after Sunday *}
                {if $nDate|date_format:'%u' == 7}
                    </tr>
                    <tr>
                {/if}
                {/for}

                {* Next month's days for the last week *} 
                {for $j = $nDate|date_format:'%u' to 6}
                <td></td>
                {/for}

                </tr>
            </tr> 
</table>
    </div>
</div>
{/block}
