{if $tags|@count > 0}
<div class="tags">
    <ul>
        <li><span class="glyphicon glyphicon-tags"></span> Теги:</li>
        {foreach from=$tags item=tag}
            <li><a href="/tag/{$tag|htmlspecialchars}" class="badge">{$tag|htmlspecialchars}</a></li>
        {/foreach}
    </ul>
</div>
{/if}