<div id="images-{$id}" class="images-popover hidden">
    <div class="popover-heading">
        Изображения
    </div>

    <div class="popover-body">
        {foreach from=$images item=image}
        <a href="{$imgPrefix}{$id}/{if !isset($isDirect)}full/{/if}{$image.fname}" target="_blank">
            <img src="{$imgPrefix}{$record.id}/{if !isset($isDirect)}thumb/{/if}{$image.fname}" alt="" class="img-responsive">
        </a>
        {/foreach}
    </div>
</div>