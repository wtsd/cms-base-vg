{extends file="index.tpl"}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i></a></li>
        <li class="active">Обратная связь</li>
    </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row normal-block">
    <div class="col-md-6">
  		{include file="includes/feedback-form.tpl"}
    </div>
    <div class="col-md-6">
        <h3>Напишите нам</h3>
        <p>Мы свяжемся с вами в самое ближайшее время.</p>
    </div>
</div>
{/block}