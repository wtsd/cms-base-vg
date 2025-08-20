{strip}
{if isset($messages)}
{foreach from=$messages item=message}
<div class="alert alert-{if isset($message.type)}{$message.type}{elseif isset($type)}{$type}{else}danger{/if} alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {$message.text}
</div>
{/foreach}
{/if}
{/strip}