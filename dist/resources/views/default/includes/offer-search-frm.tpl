{strip}
<form name="frm_searchoffer" action="/search-offer/" method="get" class="">
    <div class="form-group">
        <input type="text" name="q" value="{if isset($query)}{$query}{/if}" placeholder="Поиск" class="form-control">
        <button class="btn btn-default">Найти!</button>
    </div>
</form>
{/strip}