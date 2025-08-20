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

  <link rel="stylesheet" type="text/css" href="/web/css/main.css" />
  <!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script src="/web/js/functions.js" type="text/javascript"></script>
</head>
<body class="print">


<div class="container">
  <div class="row">
    <img alt="{$labels.global_title}" src="/web/images/logo-print.png">
  </div>
  {/strip}{block name="content-wrapper"}{/block}{strip}
</div>


{block name="analytics"}
{include file='banners/google-analytics.tpl'}
{include file='banners/yandex-metrika.tpl'}
{/block}

<script>
window.print();
</script>

</body>
</html>{/strip}