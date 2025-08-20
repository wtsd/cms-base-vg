{strip}<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Vladislav Gafurov">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="keywords" content="{block name="metakeywords"}{$meta.keywords}{/block}">
    <meta name="description" content="{block name="metadescription"}{$meta.description}{/block}">

    <link rel="shortcut icon" href="/web/images/icon.png" />

    <title>{block name="title"}{if isset($title)}{$title}{/if}{/block}</title>

    {block name="headincludes"}
      <link rel="stylesheet" type="text/css" href="/web/css/libs.min.css" />
      <link rel="stylesheet" type="text/css" href="/web/css/primary.min.css" />
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->

      <script src="https://maps.googleapis.com/maps/api/js?key={$mapKey}" type="text/javascript"></script>
      <script src="/web/js/libs.js" type="text/javascript"></script>
      <script src="/web/js/app.min.js" type="text/javascript"></script>
    {/block}
</head>
<body{if isset($environment) && $environment != 'PROD'} class="{$environment}"{/if}>

{block name="header"}{strip}
<div class="preheader container-fluid">
  <div class="container">
    <div class="row hidden-xs">
      <div class="col col-md-6">Адрес: <a href="/category/contacts/">г.{$labels.address.city}, {$labels.address.address}</a></div>
      <div class="col col-md-offset-3 col-md-3"><p class="text-right"><nobr>
        <a href="tel:{$labels.address.tel}">{$labels.address.tel}</a>
        {*if $is_authorized}
        | <a href="/cabinet/">{$labels.cabinet}</a> |
        <a href="/logout/">{$labels.caption_logout}</a>
        {else}
        | <a href="/signin/">{$labels.signin}</a> | 
        <a href="/signin/">{$labels.signup}</a>
        {/if*}
        </nobr>
        </p></div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col col-md-2 hidden-xs">
      <a href="/"><img alt="{$labels.global_title}" src="/web/images/logo.png"></a>
    </div>
    <div class="col col-md-10">
      <div class="row hidden-xs">
        <div class="col col-md-7">
          {if $config.market}
          {include file="includes/offer-search-frm.tpl" query=""}
          {/if}
        </div>
        <div class="menu col col-md-5">
          <ul class="list-inline">

            {*if $config.is_authentication}
              {if $is_authorized}
              <li><img src="{$userpic}" class="userpic img-circle"> <a href="/user/">{$fullname}</a></li>
              <li><a href="/logout/">{$labels.caption_logout}</a></li>
              {else}
              <li><span class="glyphicon glyphicon-user"></span> <a href="/login/" class="vk_auth">{$labels.caption_login}</a></li>
              {/if}
            {/if*}

          
            <li><a href="#" data-toggle="modal" data-target=".call-modal" class="btn btn-default"><strong>Заказать звонок!</strong></a></li>

          {if $config.market}
            {if $config.is_cart}
              <li><a href="/cart/" class="btn btn-default"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="cartCount">{if $cartCount > 0}{$labels.caption_cart} {$cartCount} ({$cartSum} руб.){else}Корзина 0{/if}</span></a></li>
            {/if}
          {/if}
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col col-md-12">

          <nav class="header-menu navbar" data-spy="" data-offset-top="0">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-menu">
              <span class="sr-only">Меню</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="collapse navbar-collapse" id="header-menu">
          {if $config.auto_generate_menu}
            {include file="includes/menu.tpl" menuitems=$menuitems with_submenus=true}
          {/if}
            <ul class="nav navbar-nav visible-xs affix-el" data-spy="affix" data-offset-top="60" data-offset-bottom="200">
              {if $config.market}
              <li>
                {if $config.is_cart}<a href="/cart/"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="">{if $cartCount > 0}{$labels.caption_cart} {$cartCount} ({$cartSum} руб.){else}Корзина 0{/if}</span></a>{/if}
                <a href="/callrequest/"><strong>Заказать звонок!</strong></a>
              </li>
              {/if}
              <li>Адрес: г.{$labels.address.city}, {$labels.address.address}, телефон: {$labels.address.tel}</li>
              {if $config.market}
              <li>
                {include file="includes/offer-search-frm.tpl" query=""}
              </li>
              {/if}
            </ul>
          </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
{/strip}{/block}{strip}
<div class="full-w container-fluid">{/strip}{block name="postheader"}{/block}{strip}</div>

<div class="container">
  {/strip}{block name="content-breadcrumb"}{/block}{strip}

  {* CONTENT *}
  {/strip}{block name="content-wrapper"}{/block}{strip}

  {* /CONTENT *}
</div>
<div class="container-fluid">
{/strip}{block name="prefooter"}{/block}{strip}
</div>
{/strip}{block name="footer"}{strip}
<div class="container-fluid footer">
  <div class="container">
    <div class="row">
      <div class="col col-md-4 col-xs-6">
        <span class="h4">Навигация</span>
          <a href="/category/about/">О Компании</a> |
          {if $config.market}<a href="/products/">Каталог</a> |{/if}
          <a href="/category/contacts/">Контакты</a> |
          <a href="/category/promo/">Акции</a> |
          <a href="/category/news/">Блог</a>
        <div class="subscribe">
            <span class="h4">Подписка</span>
            <p>Вы можете подписаться на нашу почтовую рассылку и быть всегда в курсе последних событий и обновлений</p>
            <div class="frmSubs"><input type="email" name="email" placeholder="ваш email" class="subscriptionEmail"><button class="btn btn-success doSubscribe">Подписаться</button></div>
        </div>
      </div>
      <div class="col col-md-4 col-xs-6">
        <div class="vk-group">
        {include file="includes/vk-group.tpl" gid="39761332"}
        </div>
      </div>
      <div class="col col-md-3 col-md-offset-1 col-xs-12">
        <span class="h4">Контактная информация</span>
        <p class="address">
          <i class="fa fa-map-marker"></i> <a href="/category/contacts/">г. {$labels.address.city}, {$labels.address.address}</a>  <br>
          <i class="fa fa-mobile"></i> {$labels.address.tel}&nbsp; 
          <i class="fa fa-envelope-o"></i> <a href="mailto:{$labels.address.email}">{$labels.address.email}</a>
        </p>

        <div class="socials">
          <a href="https://www.facebook.com/" target="_blank"><img src="/web/images/socs/circular/facebook-64.png" alt="Facebook"></a>
          <a href="https://plus.google.com/" target="_blank"><img src="/web/images/socs/circular/googleplus-64.png" alt="Google+"></a>
          <a href="https://twitter.com/" target="_blank"><img src="/web/images/socs/circular/twitter-64.png" alt="Twitter"></a>
          <a href="https://vk.com/" target="_blank"><img src="/web/images/socs/circular/vk-64.png" alt="VKontakte"></a>
        </div> 
      </div>
    </div>
  </div>
    <div class="container">
    <div class="row">
      <p class="text-right">© 2012 — 2016, Все права защищены | Разрабокта <a href="https://vk.com/vgafurov">VG</a> | <a href="/feedback/" rel="nofollow">Форма обратной связи</a></p>
    </div>
  </div>
</div>

{/strip}{/block}{strip}
{/strip}{block name="feedback"}{strip}
{if $config.is_feedback}
<noindex>
  <div class="feedback-left">
    <div class="btn disclose"><span class="glyphicon glyphicon-envelope"></span></div>
      <div id="frm">
        <button type="button" class="close doHide" data-dismiss="frm">&times;</button>
        {include file="includes/feedback-form.tpl"}
      </div>
  </div>
</noindex>
{/if}
{/strip}{/block}
{block name="cart-helpers"}{strip}
{if $config.market}
  {if $config.is_cart}
  <noindex>
  <div class="cart-contents-float"></div>

  <div class="modal fade cart-modal" tabindex="-1" role="dialog" aria-labelledby="Корзина" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
        Корзина
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Вы хотите перейти в корзину и оформить заказ или продолжить покупки?</p>
      </div>
      <div class="modal-footer">
        <a href="#" data-dismiss="modal" class="btn btn-default">Продолжить покупки</a>
        <a href="/cart/" class="btn btn-primary">Перейти в корзину</a>
      </div>
      </div>
    </div>
  </div>
  </noindex>
  {/if}
{/if}
{/strip}{/block}{strip}
{/strip}{block name="totop"}<a class="top" href="#top"></a>{/block}{strip}
{if $config.req_calback}

<noindex>

<div class="modal fade call-modal" tabindex="-1" role="dialog" aria-labelledby="Звонок" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      Заказ обратного звонка
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body">
      {include file='includes/frm-request-callback.tpl'}
    </div>
    <div class="modal-footer">
      <a href="#" data-dismiss="modal" class="btn btn-default">Отменить заказ звонка</a>
    </div>
    </div>
  </div>
</div>
</noindex>
{/if}
{/strip}{block name="scripts"}{/block}{strip}
{/strip}{block name="analytics"}
{include file='banners/google-analytics.tpl'}
{include file='banners/yandex-metrika.tpl'}
{/block}{strip}

</body>
</html>{/strip}
