{extends file="index.tpl"}

{block name="metakeywords"}{if isset($row.meta_keywords)}{$row.meta_keywords}{else}{$labels.meta.keywords}{/if}{/block}
{block name="metadescription"}{if isset($row.meta_description)}{$row.meta_description}{else}{$labels.meta.description}{/if}{/block}

{block name="title"}{if isset($row)}{$row.title}{else}{$labels.global_title}{/if}{/block}


{block name="content-wrapper"}
{strip}
<div class="install">
    <div class="home-news">
        {include file="includes/offer-search-frm.tpl" query=""}
    </div>
    
    {if isset($row)}
    <div class="installation">
        <h1>{$row.h1}</h1>
        {$row.f_text|strip}
    </div>
    {/if}


    <div class="features">
        <div class="feature">
            <h2>Гибкость</h2>
            <img src="/web/images/home/flexibility.png">
            <p>Полная расширяемость функционала и возможность использования любого шаблона.</p>
        </div>
        <div class="feature">
            <h2>Лёгкость</h2>
            <img src="/web/images/home/simplicity.png">
            <p>Простой алгоритм работы с сайтом.</p>
            <p>Управление всем имеющимся контентом.</p>
        </div>
        <div class="feature">
            <h2>Уникальность</h2>
            <img src="/web/images/home/uniqueness.png">
            <p>Узнаваемость и полная персонализация сайта. Каждая страница может иметь уникальную структуру, но сохранять общий стиль.</p>
        </div>
    </div>

    {if $config.with_news}
    <div class="home-news scroll-vis">
        <h3><i class="fa fa-newspaper-o"></i> Последние новости</h3>
        {include file="includes/news.tpl"}
    </div>
    {/if}

    {if $config.offers_special}
    <div class="home-offers scroll-vis">
        <h3>Спецпредложения</h3>
        {include file="includes/special-offers.tpl"}
    </div>
    {/if}
</div>
{/strip}
{/block}