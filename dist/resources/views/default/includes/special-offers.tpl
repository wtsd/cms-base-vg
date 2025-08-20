{strip}{if count($products) > 0}
    <div class="error" style="display:none;"></div>
    <div class="offers-block">
      {foreach from=$products item=offer}
        {include file='includes/single-offer.tpl'}
      {/foreach}
    </div>
{else}
    <p>В настоящее время нет спецпредложений. Посмотрите <a href="/products/">каталог полностью</a>.</p>
{/if}{/strip}