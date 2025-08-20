{strip}<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <meta name="description" content="">
    <meta name="author" content="Vladislav Gafurov">
    
    <title>{block name="title"}{$labels.title} &mdash; {$labels.ver}{/block}</title>

    <link rel="stylesheet" type="text/css" href="/web/css/admin-libs.css">
    <link rel="stylesheet" type="text/css" href="/web/css/admin.min.css">

    <script>var prefix = '{$prefix}'; var CKEDITOR_BASEPATH = '/resources/assets/ckeditor/';</script>
    
    <script src="/web/js/admin-libs.js" type="text/javascript"></script>
    <script src="/web/js/admin.js" type="text/javascript"></script>
</head>
<body class="{$environment}">

{/strip}{block name="header"}

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Меню</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="pull-left" href="/{$prefix}/">
            <img src="/web/images/logo-sm.png" alt=""></a>&nbsp;
          <a class="navbar-brand" href="/{$prefix}/">{$config.site_name}</a>
        </div>


        <div id="navbar" class="navbar-collapse collapse">
          {if $user->isAuthorized()}
          <ul class="nav navbar-nav">
            <li><a href="/{$prefix}/article/add/" title="Добавить пост">
              <i class="fa fa-pencil"></i> <span class="hidden-lg">Добавить пост</span>
            </a></li>
            <li><a href="/{$prefix}/article/browse/" title="Посты">
              <i class="fa fa-list"></i>  <span class="hidden-lg">Посты</span>
            </a></li>
            <li class="dropdown">
              <a href="#" title="Содержание сайта" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <i class="fa fa-pencil-square-o"></i> <span class="hidden-lg">Содержание сайта</span><span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/{$prefix}/category/browse/">Все категории</a></li>
                <li><a href="/{$prefix}/category/add/">Новая категория</a></li>
                <li class="divider"></li>
                <li><a href="/{$prefix}/article/browse/">Все статьи</a></li>
                <li><a href="/{$prefix}/article/add/">Новая статья</a></li>
              </ul>
            </li>
            <li>
              <li class="dropdown">
                <a href="/{$prefix}/article/browse/" title="Галерея" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-picture-o"></i> <span class="hidden-lg">Галерея</span> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="/{$prefix}/gallery/browse/">Галереи</a></li>
                  <li><a href="/{$prefix}/image/browse/">Изображения</a></li>
                  <li class="divider"></li>
                  <li><a href="/{$prefix}/slider/browse/">Слайдеры</a></li>
                </ul>
              </li>
          </li>
          {if $config.market}
              <li class="dropdown">
                <a href="/{$prefix}/article/browse/" title="Маркет" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-shopping-cart"></i> <span class="hidden-lg">Маркет</span> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="/{$prefix}/orders/browse/"><strong>Заказы</strong></a></li>
                  <li><a href="/{$prefix}/offer/browse/">Товары</a></li>
                  <li><a href="/{$prefix}/pcategory/browse/">Типы товаров</a></li>
                  <li><a href="/{$prefix}/pspec/browse/">Характеристики</a></li>
                  <li><a href="/{$prefix}/vendor/browse/">Производители</a></li>
                  <li><a href="/{$prefix}/branch/">Филиалы</a></li>
                </ul>
              </li>
          {/if}

          {if $user->isAdmin()}
          <li class="dropdown">
            <a href="/{$prefix}/article/browse/" title="Маркет" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-users"></i> <span class="hidden-lg">Пользователи</span> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="/{$prefix}/user/">Пользователи</a></li>
                  <li><a href="/{$prefix}/group/">Группы</a></li>
                </ul>
            
          </li>
          {/if}
          </ul>
          {/if}

          <ul class="nav navbar-nav navbar-right">
            {if $user->isAuthorized()}
            <li><a href="/{$prefix}/dashboard"><i class="fa fa-dashboard"></i> <span class="hidden-lg">Дэшборд</span></a></li>

            <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                  <span class="glyphicon glyphicon-user"></span>
                  {$user->getName()}
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="/{$prefix}/profile/">Редактировать профиль</a></li>
                  <li class="divider"></li>
                  <li><a href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
                    <span class="glyphicon glyphicon-log-out"></span> 
                    Выйти
                  </a></li>
                </ul>

            <!-- <li><a href="/{$prefix}/profile/">
              <span class="glyphicon glyphicon-user"></span>
              {$user->getName()}
            </a></li> -->

            {/if}
          </ul>
          {if $user->isAuthorized()}
          <form class="navbar-form navbar-right" action="/adm/article/browse" method="get" data-action="search">
            <input type="text" class="form-control transparent-input" name="q" placeholder="Поиск…" value="{if isset($q)}{$q}{/if}">
            <button class="form-control transparent-input">
              <i class="fa fa-search"></i>
            </button>
          </form>
          {/if}
        </div>
      </div>
    </nav>

{/block}



<div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          {if $user->isAuthorized()}
          <ul class="nav nav-sidebar">
              <li{if "/$prefix/article/add/" == $smarty.server.REQUEST_URI} class="active"{/if}><a href="/{$prefix}/article/add/"><strong>Новый пост</strong></a></li>
              <li{if "/$prefix/article/browse/" == $smarty.server.REQUEST_URI} class="active"{/if}><a href="/{$prefix}/article/browse/">Все посты</a></li>
          </ul>
          {if $config.market}
          <ul class="nav nav-sidebar">
              <li{if "/$prefix/offer/add/" == $smarty.server.REQUEST_URI} class="active"{/if}><a href="/{$prefix}/offer/add/">Новый товар</a></li>
              <li{if "/$prefix/offer/browse/" == $smarty.server.REQUEST_URI} class="active"{/if}><a href="/{$prefix}/offer/browse/">Все товары</a></li>
              <li{if "/$prefix/orders/browse/" == $smarty.server.REQUEST_URI} class="active"{/if}><a href="/{$prefix}/orders/browse/">Заказы</a></li>
          </ul>
          {/if}
          {/if}
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          
          {block name="content-wrapper"}{/block}
        </div>
      </div>
    </div>
</div>

{if $user->isAuthorized()}
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="closeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Выход?!</h4>
      </div>
      <div class="modal-body">
        Вы уверены, что хотите выйти из системы?
      </div>
      <div class="modal-footer">
        <a type="button" class="btn btn-default" data-dismiss="modal">Отменить</a>
        <a href="/{$prefix}/logout/" type="button" class="btn btn-primary">Выйти</a>
      </div>
    </div>
  </div>
</div>
{/if}

<div class="modalLoading"></div>
</body>
</html>