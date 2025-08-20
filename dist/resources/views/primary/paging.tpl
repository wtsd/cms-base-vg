{if isset($pages) && $pages > 1}
    <div class="paginator">
    <ul class="pagination">
        {* First/prev page *}
        {if $curPage == 1}
            <li class="disabled"><span>&#0171;</span></li>
        {else}
            <li><a href="{$preUrl}{$curPage - 1}/">&#0171;</a></li>
        {/if}

        {for $index=1 to $pages}
            {if $index == $curPage}
                <li class="active"><a href="#">{$index}</a></li>
            {else}
                <li><a href="{$preUrl}{$index}/">{$index}</a></li>
            {/if}
        {/for}

        {* Next/last page *}
        {if $curPage == $pages}
            <li class="disabled"><span>&#0187;</span></li>
        {else}
            <li><a href="{$preUrl}{$curPage + 1}/">&#0187;</a></li>
        {/if}

    </ul>
    </div>
{/if}