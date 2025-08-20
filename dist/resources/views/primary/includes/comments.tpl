{strip}
<div class="comments" data-fid="{$fid}" data-type="{$type}">
	{foreach from=$comments item=comment}
		<div class="comment">
			<p class="name">{$comment.name|htmlspecialchars}</p>
			<p class="date">{$comment.cdate}</p>
			<p>{$comment.comment|htmlspecialchars|nl2br}</p>
		</div>
	{/foreach}
	{include file="includes/paginator.tpl" curPage=$page preUrl='#'}
</div>
{/strip}