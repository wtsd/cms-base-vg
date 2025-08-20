{strip}
<form name="frm_searchoffer" action="/search-offer/" method="get" class="frm-offer form-inline">
    <input type="text" name="q" value="{if isset($query)}{$query}{/if}" placeholder="Название товара" class="form-control">
    <button class="btn btn-default">Найти!</button>
</form>
{/strip}