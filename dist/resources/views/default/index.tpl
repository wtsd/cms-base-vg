{strip}<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Vladislav Gafurov">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="keywords" content="{block name="metakeywords"}{$meta.keywords}{/block}">
    <meta name="description" content="{block name="metadescription"}{$meta.description}{/block}">

    <link rel="shortcut icon" href="/web/images/icon.png" />

    <title>{block name="title"}{$title}{/block}</title>

    {block name="headincludes"}
      <link rel="stylesheet" type="text/css" href="/web/css/main.css" />
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->

      <script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXxcB4MSB-Oa9d8WaG4p1KmhhUrSgckTU" type="text/javascript"></script>
      <script src="/web/js/main.min.js" type="text/javascript"></script>
    {/block}
</head>
<body>

{block name="header"}
{*<div class="container">
  <div class="row">
    <div class="col col-md-3 col-md-offset-9">
      &nbsp;
    </div>
  </div>
</div>*}

<nav class="header-menu navbar navbar-default navbar-inverse" data-spy="" data-offset-top="0">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-menu">
        <span class="sr-only">Меню</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/" title="{$labels.global_title}">
        <img alt="{$labels.global_title}" src="/web/images/logo.png">
      </a>
    </div>

    <div class="collapse navbar-collapse" id="header-menu">
      {if $config.auto_generate_menu}
        {include file="includes/menu.tpl" menuitems=$menuitems with_submenus=true}
      {/if}

      <ul class="nav navbar-nav navbar-right">
        <li><a href="tel:{$labels.address.tel}">{$labels.address.tel}</a></li>
        <li><a href="#" data-toggle="modal" data-target=".call-modal"><strong>Заказать звонок!</strong></a></li>
        {if $config.is_cart}
          <li><a href="/cart/"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="cartCount">{if $cartCount > 0}{$labels.caption_cart} {$cartCount} ({$cartSum} руб.){else}Корзина пуста{/if}</span></a></li>
        {/if}
        {if $config.is_authentication}
          {if $is_authorized}
          <li><img src="{$userpic}" class="userpic img-circle"> <a href="/user/">{$fullname}</a></li>
          <li><a href="/logout/">{$labels.caption_logout}</a></li>
          {else}
          <li><span class="glyphicon glyphicon-user"></span> <a href="/login/" class="vk_auth">{$labels.caption_login}</a></li>
          {/if}
        {/if}

      </ul>

    </div>
  </div>
</nav>

{/block}


{*if $labels.slider|count > 0}
  {include file="includes/slider.tpl" slider=$labels.slider[0]}
{/if*}

<div class="container">
  {block name="content-breadcrumb"}{/block}

  {* CONTENT *}
  {block name="content-wrapper"}{/block}

  {* /CONTENT *}
</div>
{block name="footer"}{strip}
<div class="container-fluid footer">
  <div class="container">
    <div class="row-1">
      Вы можете обращаться <a href="/feedback/" rel="nofollow">к нам</a> по любым вопросам.
      {if $config.auto_generate_menu}
        {include file="includes/menu.tpl" menuitems=$menuitems with_submenus=false}
      {/if}


    </div>
    <div class="row-2">
      <p>Присоединяйтесь к нам в социальных сетях:</p>
      <div class="socials">
        <a href="https://www.facebook.com/" target="_blank"><img src="/web/images/socs/circular/facebook-64.png" alt="Facebook"></a>
        <a href="https://plus.google.com/" target="_blank"><img src="/web/images/socs/circular/googleplus-64.png" alt="Google+"></a>
        <a href="https://twitter.com/" target="_blank"><img src="/web/images/socs/circular/twitter-64.png" alt="Twitter"></a>
        <a href="https://vk.com/" target="_blank"><img src="/web/images/socs/circular/vk-64.png" alt="VKontakte"></a>
      </div>  
      <div class="subscribe">
          <span class="h4">Подписка</span>
          Вы можете подписаться на нашу почтовую рассылку и быть всегда в курсе последних событий и обновлений<br>
          <div class="frmSubs"><input type="email" name="email" placeholder="ваш email" class="subscriptionEmail"><button class="doSubscribe">Подписаться</button></div>
      </div>
    </div>
    <div class="row-3">
      <span class="h4">Контактная информация</span>
      <p class="address">
        <i class="fa fa-map-marker"></i> <a href="/category/contacts/">{$labels.address.address}</a>  <br>
      <i class="fa fa-mobile"></i> {$labels.address.tel} <br>
      <i class="fa fa-envelope-o"></i> <a href="mailto:{$labels.address.email}">{$labels.address.email}</a> 
      </p>
      <p>© 2012 — 2015, Все права защищены<br>
      Разрабокта <a href="https://vk.com/vgafurov">VG</a></p>
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
{/if}{/strip}
{/block}{strip}
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