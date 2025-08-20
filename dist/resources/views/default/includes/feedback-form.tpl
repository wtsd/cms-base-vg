{strip}
<div class="alert alert-info notification" style="display:none;"></div>

<form action="/feedback/" method="post" class="frm_feedback">
    <h2>Обратная связь</h2>
    <input type="hidden" name="token" value="{$token}" id="token" />
    {foreach from=$fields item=field}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="title">{$field.title}{if $field.required}<span class="required">*</span>{/if}</label>
                {if $field.type == 'text'}
                <input type="text" name="{$field.name|htmlspecialchars}" value="" placeholder="{$field.placeholder|htmlspecialchars}" {if $field.required} required="required"{/if} id="{$field.name}" class="form-control">
                {elseif $field.type == 'textarea'}
                <textarea name="{$field.name}" value="" placeholder="{$field.placeholder|htmlspecialchars}" {if $field.required} required="required"{/if} id="{$field.name}" class="form-control"></textarea>
                {/if}
            </div>
        </div>
    </div>
    {/foreach}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <button class="btn btn-primary">Отправить!</button>
            </div>
        </div>
    </div>
</form>
{/strip}