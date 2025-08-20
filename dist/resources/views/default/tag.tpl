{extends file="index.tpl"}

{block name="metakeywords"}{/block}
{block name="metadescription"}{/block}

{block name="title"}Результаты поиска{/block}

{block name="content-breadcrumb"}
<div class="row">
  <ul class="breadcrumb">
      <li><a href="/">Главная</a></li>
      <li class="active">Результаты поиска</li>
  </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row">
    <div class="col-md-8 normal-block">
      <h3>Результаты поиска по тегу «{$tag|htmlspecialchars}»</h3>
      {include file="includes/articles-list.tpl"}
    </div>

</div>
{/block}