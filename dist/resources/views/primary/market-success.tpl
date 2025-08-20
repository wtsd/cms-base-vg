{extends file="index.tpl"}

{block name="title"}Заказ оплачен{/block}

{block name="content-breadcrumb"}
<div class="row">
  <ul class="breadcrumb">
      <li><a href="/">{$labels.mainpage}</a></li>
      <li><a href="/cart/">Корзина</a></li>      
      <li class="active">Заказ оплачен</li>
  </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row">
	<p>Ваш заказ #{$orderId|intval} был оплачен. В скором времени с Вами свяжется наш менеджер.</p>
</div>
{/block}
