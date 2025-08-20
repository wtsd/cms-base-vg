<div class="gallery">
    {if isset($message) && $message != ''}
        <div class="message">{$message}</div>
    {/if}

    <div class="row">
    {if $type == 'galleries'}
        <h1>Галерея</h1>
        <div class="images">
        {foreach from=$galleries item=gallery}
            <div class="image">
                <a href="/gallery/{$gallery.rewrite}" title="{$gallery.name}">
                    <img src="{$path}{$gallery.id}/thumb/{$gallery.fname}" alt="{$gallery.name}">
                </a>
                <div class="name">
                    <a href="/gallery/{$gallery.rewrite}" title="{$gallery.name}">
                        {$gallery.name}
                    </a>
                </div>
            </div>
        {/foreach}
        </div>
    {else}
        <h1>{$title}</h1>
        {if $lead != ''}
        <div class="lead">{$lead}</div>
        {/if}
        {if $images|@count > 0}
        <div class="images lightbox">
        {foreach from=$images item=image}
            <div class="image">
                <a href="{$path}{$gallery.id}/full/{$image.fname}" title="{$image.name|htmlspecialchars}" target="_blank">
                    <span class="rollover"></span>
                    <img src="{$path}{$gallery.id}/thumb/{$image.fname}" alt="{$image.name|htmlspecialchars} {$image.descr|htmlspecialchars}" border="0" />
                </a>
            </div>
        {/foreach}
        </div>
        {else}
        <p>В этой галерее нет изображений.</p>
        {/if}
    {/if}
    </div>    
    <div class="row">
    {include file="includes/paginator.tpl"}
    </div>

    {include file="includes/share.tpl"}
</div>



