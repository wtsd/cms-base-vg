<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#pcat-menu">
    <span><a href="#">Показать категории товара</a></span>
  </button>
</div>
<div class="collapse navbar-collapse" id="pcat-menu">
    <ul class="nav nav-pills nav-stacked">
        <li>Каталог</li>
        {foreach from=$pcategories item=pcategory}
            <li{if $rewrite == $pcategory.rewrite} class="active"{/if}>
                <a href="/products/{if $pcategory.rewrite != ''}{$pcategory.rewrite}{else}{$pcategory.id}{/if}/"{if $rewrite == $pcategory.rewrite} class="active"{/if}>
                    {$pcategory.name} {*<span class="count">({$pcategory.count})</span>*}
                </a>

                {include file="includes/subcats-menu.tpl" subcats=$pcategory.subcats}
            </li>
        {/foreach}
        {if $specs|@count > 0}
            <li>Характеристики</li>
            {foreach from=$specs item=spec}
                <li><a href="#">{$spec.name}</a></li>
            {/foreach}
        {/if}
        {if $vendors|@count > 0}
            <li>Производители</li>
            {foreach from=$vendors item=vendor}
                <li><a href="/vendor/{$vendor.rewrite}/">{$vendor.name}</a></li>
            {/foreach}
        {/if}
    </ul>
</div>