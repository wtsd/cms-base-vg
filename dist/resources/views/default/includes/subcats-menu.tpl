{if $subcats|@count > 0}
 {*&darr;*}
<ul>
 {foreach from=$subcats item=subcat}
  <li{if $rewrite==$subcat.rewrite} class="active"{/if}>
    <a href="/products/{if $subcat.rewrite != ''}{$subcat.rewrite}{else}{$subcat.id}{/if}/"{if $rewrite == $subcat.rewrite} class="active"{/if}>
        {$subcat.name} {*<span class="count">({$subcat.count})</span>*}
    </a> 
     {if $subcat.subcats|@count}
        {include file="includes/subcats-menu.tpl" subcats=$subcat.subcats}
     {/if}
     </li>
 {/foreach}
</ul>
{/if}
