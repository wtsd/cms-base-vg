{extends file="index.tpl"}

{block name="metakeywords"}{$row.meta_keywords}{/block}
{block name="metadescription"}{$row.meta_description}{/block}

{block name="title"}{if isset($row)}{if $row.title != ''}{$row.title}{else}{$row.name}{/if}{else}{$labels.global_title}{/if}{/block}

{block name="content-breadcrumb"}
<div class="row">
  <ul class="breadcrumb">
      <li><a href="/"><i class="fa fa-home"></i></a></li>
      {if isset($row.breadcrumb)}
        {foreach from=$row.breadcrumb item=bcategory}
          {if $bcategory.rewrite}
          <li><a href="/category/{$bcategory.rewrite}">{$bcategory.name}</a></li>
          {/if}
        {/foreach}
      {/if}
      <li class="active">{$row.name}</li>
  </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row">
  {if isset($row) && $row|count > 0}
    <div class="col col-md-8 normal-block">

      <div class="category-name">
        <h1>{$row.h1}</h1>
      </div>
      {if $row.lead != ''}
        <div class="category-lead">{$row.lead}</div>
      {/if}
      {if $row.is_img}
        <div class="category-image">
          <a href="/category/{$row.rewrite}" rel="nofollow">
            <img src="/img/category/{$row.id}/{$row.fname}" alt="" />
          </a>
        </div>
      {/if}
      <div class="category-ftext">
        {$row.f_text}
      </div>

      {include file="includes/articles-list.tpl" articles=$row.articles}

      {if isset($row.gallery_id) && $row.gallery_id > 0}
        {include file="includes/gallery-contents.tpl"}
      {/if}

    </div>

    <div class="col col-md-3 col-md-offset-1 normal-block hidden-xs">
      <h4>Навигация</h4>
      <ul class="nav nav-pills nav-stacked">
      {if $row.subcats|@count > 0}
        {foreach from=$row.subcats item=subcat}
        <li><a href="{$subcat.url}">{$subcat.name}</a></li>
        {/foreach}
      {elseif $row.adjcats|@count > 0}
        {foreach from=$row.adjcats item=adjcat}
          <li{if $adjcat.url == $smarty.server.REQUEST_URI} class="active"{/if}>
            <a href="{$adjcat.url}">{$adjcat.name}</a>
          </li>
        {/foreach}
      {/if}
      </ul>
    </div>
    {/if}

</div>
{/block}