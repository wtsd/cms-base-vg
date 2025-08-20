{if !isset($sliderImages)}
<div id="carousel_{$slider.name}" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  {if $slider.indicators == 1}
  <ol class="carousel-indicators">
    {foreach from=$slider.slides item=sliderIt name=points}
        <li data-target="#carousel_{$slider.name}" data-slide-to="{$smarty.foreach.points.index}"{if $smarty.foreach.points.index == 0} class="active"{/if}></li>
    {/foreach}
  </ol>
  {/if}

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
  {foreach from=$slider.slides item=sliderIt name=slides}
    <div class="item{if $smarty.foreach.slides.index == 0} active{/if}">
      <img src="/img/slider/{$slider.id}/{$sliderIt.fname}" alt="{$sliderIt.h2|strip_tags}">
      <div class="carousel-caption">
        {$sliderIt.text}
      </div>
    </div>
  {/foreach}
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel_{$slider.name}" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel_{$slider.name}" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div>
{else}
<div id="carousel" class="carousel slide" data-ride="carousel">
  <!-- Wrapper for slides -->
  <ol class="carousel-indicators">
    {foreach from=$sliderImages item=sliderIt name=points}
        <li data-target="#carousel" data-slide-to="{$smarty.foreach.points.index}"{if $smarty.foreach.points.index == 0} class="active"{/if}></li>
    {/foreach}
  </ol>

  <div class="carousel-inner">
  {foreach from=$sliderImages item=image name=slides}
    <div class="item{if $smarty.foreach.slides.index == 0} active{/if}">
      <img src="{$dir}/{$image.fname}">
    </div>
  {/foreach}
  </div>
  <a class="left carousel-control" href="#carousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>

</div>
{/if}