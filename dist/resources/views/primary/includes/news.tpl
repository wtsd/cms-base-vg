{strip}
{if isset($news) && $news|count > 0}


        {foreach from=$news item=article}
        <div class="media">
                {if isset($article.photos[0])}
          <div class="media-left">
            <a href="/article/{$article.rewrite}">
              <img class="media-object" src="/img/article/{$article.id}/thumb/{$article.photos[0].fname}" alt="...">
            </a>
          </div>
              {/if}
          <div class="media-body">
            <a href="/article/{$article.rewrite}"><h4 class="media-heading">{$article.name}</h4></a>
            {$article.lead|strip}
          </div>
        </div>
        
        {/foreach}
    <p><a href="/category/news/" class="btn_more">Все новости</a></p>
{else}
    <p>Нет новостей</p>
{/if}
{/strip}