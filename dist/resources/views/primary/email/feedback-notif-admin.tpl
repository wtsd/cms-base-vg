<p>Добрый день!</p>
<p>Отправлено сообщение с сайта <a href="{$site}">{$site}</a>:</p>

<div style="border:1px solid #808080;padding:10px;margin:10 auto;width:400px;min-height:100px;">
    {$arr.msg|htmlspecialchars|nl2br}
</div>
<dl>
    <dt style="font-weight:bold;">Дата и время отправки:</dt>
    <dd>{$date}</dd>
    {foreach from=$fields item=field}
        <dt style="font-weight:bold;">{$field.title}:</dt>
        <dd>{$arr[$field.name]}</dd>
    {/foreach}
    <dt style="font-weight:bold;">IP-адрес:</dt>
    <dd>{$ip}</dd>
</dl>

<p>Отвечать на это письмо не нужно, так как я всего лишь робот, доставивший весть о письме.</p>
<p>Хорошего дня!</p>