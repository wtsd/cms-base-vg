{extends file="index.tpl"}

{block name="title"}{$labels.callrequest.title}{/block}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i></a></li>

        <li class="active">{$labels.callrequest.title}</li>
    </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row normal-block">
{if $status == 'success'}
    <h2>{$labels.callrequest.successtitle}</h2>
    <p>{$labels.callrequest.successmsg}</p>
{else}
    <h2>{$labels.callrequest.title}</h2>
    {include file="includes/frm-request-callback.tpl"}
{/if}
</div>
{/block}