{extends file="index.tpl"}

{block name="metakeywords"}{$offer.meta_keywords}{/block}
{block name="metadescription"}{$offer.meta_description}{/block}

{block name="title"}{if isset($offer)}{if $offer.title != ''}{$offer.title}{else}{$offer.name} - {$labels.global_title}{/if}{else}{$labels.global_title}{/if}{/block}

{block name="content-breadcrumb"}{strip}
<div class="row">
  <ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i></a></li>
    <li><a href="/products/">Каталог товаров</a></li>
    <li><a href="/products/{$offer.pcat_rewrite}/">{$offer.pcat_name}</a></li>
    <li class="active">{$offer.name}</li>
  </ul>
</div>
{/strip}{/block}

{block name="content-wrapper"}{strip}
{if isset($offer) && $offer|@count > 0}
<div class="normal-block single-offer">
  <input type="hidden" name="offer_id" value="{$offer.id}" id="offer_id" />
  <h1>{$offer.h1}</h1>
  <div class="row">
      <div class="img lightbox col col-md-4 col-xs-12">

        {if $offer.photo != ''}
        <a href="/img/offer/{$offer.id}/full/{$offer.photo}" target="_blank">
        <img data-src="/img/offer/{$offer.id}/thumb/{$offer.photo}" src="/img/offer/{$offer.id}/thumb/{$offer.photo}" alt="{$offer.name|htmlspecialchars}" class="img-polaroid main">
        </a>
        {else}
        <a href="#">
        <img data-src="/img/nopreview.jpg" src="/img/nopreview.jpg" alt="" class="img-polaroid main">
        </a>
        {/if}

        
        <div class="previews">
          {foreach from=$offer.images item=image}
          <a href="/img/offer/{$offer.id}/full/{$image.fname}"><img src="/img/offer/{$offer.id}/thumb/{$image.fname}" class="thumb" /></a>
          {/foreach}
        </div>
      </div>
   
      <div class="col col-md-8 col-xs-12 description">
        <table class="table">
          <tbody>
            <tr>
              <th>Название:</th>
              <td>{$offer.name}</td>
            </tr>
            <tr>
              <th>Категория:</th>
              <td><a href="/products/{$offer.pcat_rewrite}/">{$offer.pcat_name}</a></td>
            </tr>

            <tr>
              <th>Цена:</th>
              {if $offer.price > 0}
              <td>{$offer.price_label}</td>
              {else}
              <td>Цену следует уточнить у оператора по телефону.</td>
              {/if}
            </tr>

            {if isset($offer.vendor_name) && $offer.vendor_name != ''}
            <tr>
              <th>Производитель:</th>
              <td>
                  <a href="/vendor/{$offer.vendor_rewrite}/" rel="nofollow" target="_blank">
                    {$offer.vendor_name}
                  </a>
              </td>
            </tr>
            {/if}

            <tr>
              <th>Лот:</th>
              <td>#{$offer.id}</td>
            </tr>

            <tr>
              <th>Ссылка на товар:</th>
              <td><a href="//{$config.base_url}/offer/{$offer.rewrite}/">http://{$config.base_url}/offer/{$offer.rewrite}/</a></td>
            </tr>

            {if $config.is_cart}
            <tr>
              <th>Количество:</th>
              <td>
                <a href="#" class="quantity-change badge" data-action="sub" data-offer="{$offer.id}">-</a>
                &nbsp;
                <input type="number" min="0" value="1" class="quantity" data-offer="{$offer.id}">
                &nbsp;
                <a href="#" class="quantity-change badge" data-action="add" data-offer="{$offer.id}">+</a>
              </td>
            </tr>
            {/if}
          </tbody>
        </table>
       
       {if $config.is_cart}
        <div class="controls">
          <div class="btn-group">
            <button class="btn btn-default addToCart"><span class="glyphicon glyphicon-shopping-cart"></span> Добавить в корзину</button>
            {*<button class="btn btn-default doPrint"><span class="glyphicon glyphicon-print"></span> Распечатать</button>*}
          </div>
        </div>
        {/if}
      </div>

  {if $offer.post_desc != ''}
    <div class="category-text">
       {$offer.post_desc}
    </div>
  {/if}
  </div>

  <div class="row">
    
    <div class="col col-md-12">
        <div role="tabpanel">
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#descr" aria-controls="descr" role="tab" data-toggle="tab">Описание</a></li>
            {if isset($offer.specs) && count($offer.specs) > 0}
            <li role="presentation">
              <a href="#specs" aria-controls="specs" role="tab" data-toggle="tab">
                Характеристики
              </a>
            </li>
            {/if}
            <li role="presentation">
              <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">
                Отзывы <span class="comments-count">{if $offer.comment_count > 0}({$offer.comment_count}){/if}</span>
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="descr">
              <div class="panel">
                <div class="panel-body">
                {$offer.descr}
                </div>
              </div>
            </div>
            {if isset($offer.specs) && count($offer.specs) > 0}
            <div role="tabpanel" class="tab-pane" id="specs">
              <div class="panel">
                <div class="panel-body">
                  <table class="table">
                    <tbody>
                    {foreach from=$offer.specs item=spec}
                      <tr>
                      {if $spec.stype == 4} {*color*}
                        <td>{$spec.name}</td>
                        <td><div class="color-box" style="background:{$spec.val};"></div></td>
                      {else}
                        <td>{$spec.name}</td>
                        <td>{$spec.val}</td>
                      {/if}
                      </tr>
                    {/foreach}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            {/if}
            <div role="tabpanel" class="tab-pane" id="comments">
              <div class="panel">
                <div class="panel-body">
                  <div class="row">
                  <div class="col col-md-6">
                    {include file="includes/frm-comment.tpl" type="offer" fid=$offer.id}
                  </div>
                  <div class="col col-md-6">
                    Правила размещения отзывов о товаре.
                  </div>
                </div>
                <div class="comments-container">
                  {include file="includes/comments.tpl" comments=$offer.comments type="offer" page=1 fid=$offer.id pages=$offer.cmntpages}
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>            
  </div>
</div>
{/if}
{/strip}{/block}

{block name="scripts" append}
{literal}<script>(function () {startOfferRoutine();})();</script>{/literal}
{/block}