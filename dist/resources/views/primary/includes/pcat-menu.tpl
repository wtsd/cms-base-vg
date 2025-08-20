<div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#pcat-menu">
        <a href="#">Каталог <i class="fa fa-ellipsis-v"></i></a>
    </button>
</div>
<div class="collapse navbar-collapse" id="pcat-menu">
    <ul class="nav nav-pills nav-stacked">
        {foreach from=$pcategories item=pcategory}
            <li{if $rewrite == $pcategory.rewrite} class="active"{/if}>
                <a href="/products/{if $pcategory.rewrite != ''}{$pcategory.rewrite}{else}{$pcategory.id}{/if}/"{if $rewrite == $pcategory.rewrite} class="active"{/if}>
                    {$pcategory.name} {*<span class="count">({$pcategory.count})</span>*}
                </a>

                {include file="includes/subcats-menu.tpl" subcats=$pcategory.subcats}
            </li>
        {/foreach}
        {if isset($specs) && $specs|@count > 0}
            <li>Характеристики</li>
            {foreach from=$specs item=spec}
                <li><a href="#">{$spec.name}</a></li>
            {/foreach}
        {/if}
        {if isset($vendors) && $vendors|@count > 0}
            <li>Производители</li>
            {foreach from=$vendors item=vendor}
                <li><a href="/vendor/{$vendor.rewrite}/">{$vendor.name}</a></li>
            {/foreach}
        {/if}
    </ul>
</div>