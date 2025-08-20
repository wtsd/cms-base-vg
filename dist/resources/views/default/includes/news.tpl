{strip}
{if isset($news) && $news|count > 0}
    <ul class="news">
        {foreach from=$news item=article}
        <li>
            {*<img src="/img/art/{$article.id}/0.jpg" alt="">*}
            <div class="date">
                <a href="{$article.rewrite}">
                    {$article.cdate|date_format}
                </a>
            </div>
            <div class="text">
                <h5><a href="/article/{$article.rewrite}">{$article.name}</a></h5>
                <p>{$article.lead|strip}</p>
            </div>
        </li>
        {/foreach}
    </ul>
    <p><a href="/category/news/" class="btn_more">Все новости</a></p>
{else}
    <p>Нет новостей</p>
{/if}
{/strip}