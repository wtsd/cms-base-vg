{extends file="index.tpl"}

{block name="metakeywords"}{if isset($row.meta_keywords)}{$row.meta_keywords}{else}{$labels.meta.keywords}{/if}{/block}
{block name="metadescription"}{if isset($row.meta_description)}{$row.meta_description}{else}{$labels.meta.description}{/if}{/block}

{block name="title"}{if isset($row.title)}{$row.title}{else}{$labels.global_title}{/if}{/block}

{block name="postheader"}
<div class="container home">
        <div class="row">
            <div class="col col-md-2">
                {if isset($pcategories) and count($pcategories) > 0}
                {include file="includes/pcat-menu.tpl" rewrite="/" specs=null vendors=null}
                {/if}
            </div>
            <div class="col col-md-10">
                {if count($slider) > 0}
                <div class="row">
                    <div class="col col-md-12">
                        {include file="includes/slider.tpl" slider=$slider[0]}
                    </div>
                </div>
                {/if}
                <div class="row">
                    <div class="col col-md-12">
                        {if isset($specialProducts)}
                        <h4>Специальные предложения</h4>
                        {include file="includes/special-offers.tpl" products=$specialProducts}
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i></a></li>
        <li class="active">Главная</li>
    </ul>
</div>
{/block}

{block name="content-wrapper"}{strip}
<div class="container">
    <div class="row">
        <div class="col col-md-12">
            {if isset($latestProducts)}
            <h4>Последние поступления</h4>
            {include file="includes/latest-offers.tpl" products=$latestProducts}
            {/if}
        </div>
    </div>
    <div class="row">
        {if isset($row.lead)}
        {$row.lead}
        {/if}
    </div>
    <div class="row">
        <div class="col col-md-6">
            {if isset($row.h1)}
            <h1>{$row.h1}</h1>
            {$row.f_text|strip}
            {/if}
        </div>
        <div class="col col-md-6">
            {if $config.with_news}
                <h3><i class="fa fa-newspaper-o"></i> Новости из блога</h3>
                {include file="includes/news.tpl"}
            {/if}
        </div>
    </div>
</div>
{/strip}{/block}

{*block name="prefooter"}{strip}
<div class="container">
    <div class="row">
        <div class="col col-md-4">
            <div class="row">
                <div class="col col-md-6">
                    Заказ и доставка
                </div>
                <div class="col col-md-6">
                    О Компании
                </div>
            </div>
        </div>
        <div class="col col-md-4">
            Адрес
        </div>
        <div class="col col-md-4">
            {include file="includes/vk-group.tpl" gid="39761332"}
        </div>
    </div>
</div>
{/strip}{/block*}