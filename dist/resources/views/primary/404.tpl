{extends file="index.tpl"}

{block name="meta"} {/block}

{block name="title"}{$labels.errortitle}{/block}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i></a></a></li>
        <li class="active">Не найдено!</li>
    </ul>
</div>
{/block}

{block name="content-wrapper"}{strip}
<div class="container">
    <div class="error-description normal-block">
	  <h3>{$labels.msg404}</h3>
      {if isset($url)}
      {assign var="url" value=$url|htmlspecialchars}
      <p>{$labels.msg404url|sprintf:"<code>$url</code>"}</p>
      {/if}
	  <p><a href="/">{$labels.tomain}</a></p>
  	</div>
</div>
{/strip}{/block}