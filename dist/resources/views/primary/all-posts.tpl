{extends file="index.tpl"}

{block name="metakeywords"}{if isset($row.meta_keywords)}{$row.meta_keywords}{else}{$labels.meta.keywords}{/if}{/block}
{block name="metadescription"}{if isset($row.meta_description)}{$row.meta_description}{else}{$labels.meta.description}{/if}{/block}

{block name="title"}{if isset($row)}{$row.title}{else}{$labels.global_title}{/if}{/block}

{block name="postheader"}
{/block}


{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i></a></li>
        <li class="active">Все посты</li>
    </ul>
</div>
{/block}

{block name="content-wrapper"}{strip}
<div class="container">
    <div class="row">
        <div class="col col-md-12">
            {include file="includes/articles-list.tpl" articles=$posts}
        </div>
    </div>
</div>
{/strip}{/block}