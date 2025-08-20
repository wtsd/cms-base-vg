{strip}
{if $menuitems|count > 0}
<ul class="nav navbar-nav">
  {foreach from=$menuitems item=menu}
  <li{if $uri == $menu.url} class="active"{/if}>
    
    {if $menu.sub|count > 0 && $with_submenus}
      <li class="dropdown">
        <a href="{$menu.url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{$menu.name|stripslashes} <span class="caret"></span></a>

        <ul class="dropdown-menu" role="menu">
          <li><a href="{$menu.url}">{$menu.name|stripslashes}</a></li>
          {foreach from=$menu.sub item=submenu}
          <li><a href="{$submenu.url}">{$submenu.name|stripslashes}</a></li>
          {/foreach}
        </ul>
      </li>
    {else}
      <a href="{$menu.url}">{$menu.name|stripslashes}</a>
    {/if}
  </li>
  {/foreach}
</ul>

{/if}
{/strip}