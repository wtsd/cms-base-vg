<div class="col col-md-12">
    {if isset($message) && $message != ''}
        <div class="message">{$message}</div>
    {/if}

    <div class="gallery">
    {if $galleries|@count > 0}
        <h1>Галерея</h1>
        <div class="galleries">
        {foreach from=$galleries item=gallery}
            <div class="image">
                <a href="/gallery/{$gallery.rewrite}" title="{$gallery.name}">
                    <img src="{$path}{$gallery.id}/thumb/{$gallery.fname}" alt="{$gallery.name}" class="img-responsive">
                </a>
                <div class="name">
                    <a href="/gallery/{$gallery.rewrite}" title="{$gallery.name}">
                        {$gallery.name}
                    </a>
                </div>
            </div>
        {/foreach}
        </div>
    {/if}
    {if isset($images)}
        <h1>{$title}</h1>
        {if $lead != ''}
        <div class="lead">{$lead}</div>
        {/if}
        {if $images|@count > 0}
        <div class="images">
        {foreach from=$images item=image}
            <div class="image">
                <a href="{$path}{$gallery.id}/full/{$image.fname}" title="{$image.name|htmlspecialchars}" target="_blank">
                    <span class="rollover"></span>
                    <img src="{$path}{$gallery.id}/thumb/{$image.fname}" alt="{$image.name|htmlspecialchars} {$image.descr|htmlspecialchars}" border="0" class="img-responsive">
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
