{strip}{if count($products) > 0}
    <div class="error" style="display:none;"></div>
    <div class="offers-block">
      {foreach from=$products item=offer}
        {include file='includes/single-offer.tpl'}
      {/foreach}
    </div>
{/if}{/strip}