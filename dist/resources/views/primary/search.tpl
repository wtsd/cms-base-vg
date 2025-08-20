{extends file="index.tpl"}

{block name="title"}{$title}{/block}


{block name="content-breadcrumb"}{strip}
<div class="row">
  <ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i></a></li>
    <li class="active">Поиск</li>
  </ul>
</div>
{/strip}{/block}

{block name="content-wrapper"}
<div class="row search-results normal-block">
    {include file='includes/frm-search.tpl'}
    {if $type == 'results'}
        <h3>Результаты поиска</h3>
        {if !isset($contents)}
            <div class="info">
                Поисковый запрос: {$query|htmlspecialchars}
            </div>
            <div class="cat-results">
                <h4>Разделы сайта</h4>
                {if count($categories) > 0}
                    {foreach from=$categories item=result}
                        <div class="single-result">
                            <h5><a href="/category/{$result.rewrite}">{$result.name}</a></h5>
                            <div class="lead">
                                {$result.lead}
                            </div>
                        </div>
                    {/foreach}
                {else}
                    <p>Нет результатов.</p>
                {/if}
            </div>
            <div class="art-results">
                <h4>Статьи</h4>
                {if count($articles) > 0}
                    {foreach from=$articles item=result}
                        <div class="single-result">
                            <h5><a href="/article/{$result.rewrite}">{$result.name}</a></h5>
                            <div class="lead">
                                {$result.lead}
                            </div>
                        </div>
                    {/foreach}
                {else}
                    <p>Нет результатов.</p>
                {/if}
            </div>
            <div class="pcat-results">
                <h4>Каталог товаров</h4>
                {if count($pcategories) > 0}
                    {foreach from=$pcategories item=result}
                        <div class="single-result">
                            <h5><a href="/products/{$result.rewrite}">{$result.name}</a></h5>
                            <div class="lead">
                                {$result.descr}
                            </div>
                        </div>
                    {/foreach}
                {else}
                    <p>Нет результатов.</p>
                {/if}
            </div>
            <div class="offer-results">
                <h4>Продукция</h4>
                {if count($offers) > 0}
                    <div class="offers-block">
                        <ul>
                        {foreach from=$offers item=result}
                                <li>
                                    {include file="includes/single-offer.tpl" offer=$result}
                                </li>
                        {/foreach}
                        </ul>
                    </div>
                {else}
                    <p>Нет результатов.</p>
                {/if}
            </div>
        {else}
            {$contents}
        {/if}
    {/if}
</div>
{/block}