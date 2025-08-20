{extends file="index.tpl"}

{block name="title"}Поддержите проект{/block}

{block name="content-breadcrumb"}
<div class="row">
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i></a></li>

        <li class="active">Поддержи проект</li>
    </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row article normal-block">
    <h2>Поддержи проект</h2>
    <p>Если вы хотите как-то меня поблагодарить, но стесняетесь написать лично, можете отправить любую сумму на мой кошелёк.</p>
    <iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/donate.xml?account={$yandex.account}&quickpay=donate&payment-type-choice=on&default-sum={$yandex.defaultsum}&targets={$yandex.targets|urlencode}&project-name={$yandex.projectname|urlencode}&project-site={$yandex.projectsite|urlencode}&button-text=05&fio=on&mail=on&successURL={$yandex.successurl|urlencode}" width="508" height="105"></iframe>
</div>
{/block}
