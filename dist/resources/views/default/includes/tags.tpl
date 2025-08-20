{if $tags|@count > 0}
<div class="tags">
    <div><span class="glyphicon glyphicon-tags"></span> Теги:</div>
    <ul>
        {foreach from=$tags item=tag}
            <li><a href="/tag/{$tag|htmlspecialchars}" class="badge">{$tag|htmlspecialchars}</a></li>
        {/foreach}
    </ul>
</div>
{/if}