{extends file="index.tpl"}
{block name="content-wrapper"}
<div class="row">
    <p>{$msg} <a href="{$url}">{$url}</a></p>
    <script>location.href="{$url}";</script>
</div>
{/block}