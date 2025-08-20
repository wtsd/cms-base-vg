{strip}
{if !isset($tabindex)}
{assign var="tabindex" value=''}
{/if}
<div class="form-group{* label-floating*}">
    {if isset($label)} 
    <label for="{$name}"{* class="control-label"*}>{$label}</label>
    {/if}
    {if isset($addon)}
    <div class="input-group">
          <div class="input-group-addon{if $type == 'date'} datepicker-icon" data-related="{$name}{/if}">
                <i class="{$addon}"></i>
            </div>
    {/if}
{if $type == 'text'}
    <input type="text" class="form-control" name="{$name}" id="{$name}" value="{if isset($value)}{$value|htmlspecialchars}{/if}" placeholder="{if isset($placeholder)}{$placeholder}{/if}" data-field="{$name}" tabindex={$tabindex}>
{/if}

{if $type == 'number'}
    <input type="number" {if isset($min)}min="{$min}"{/if}  {if isset($max)}max="{$max}"{/if} class="form-control" name="{$name}" id="{$name}" value="{if isset($value)}{$value|htmlspecialchars}{/if}" placeholder="{if isset($placeholder)}{$placeholder}{/if}" data-field="{$name}">
{/if}

{if $type == 'email'}
    <input type="email" class="form-control" name="{$name}" id="{$name}" value="{if isset($value)}{$value|htmlspecialchars}{/if}" placeholder="{if isset($placeholder)}{$placeholder}{/if}" data-field="{$name}" tabindex={$tabindex}>
{/if}

{if $type == 'password'}
    <input type="password" class="form-control" name="{$name}" id="{$name}" value="{if isset($value)}{$value|htmlspecialchars}{/if}" placeholder="{if isset($placeholder)}{$placeholder}{else}********{/if}" tabindex={$tabindex}>
{/if}

{if $type == 'textarea'}
    <textarea class="form-control" name="{$name}" id="{$name}" placeholder="{if isset($placeholder)}{$placeholder}{/if}" data-field="{$name}">{if isset($value)}{$value}{/if}</textarea>
{/if}

{if $type == 'select'}
    <select class="form-control" name="{$name}" data-field="{$name}">
    {if isset($placeholder)}
        <option>{$placeholder}</option>
    {/if}
    {if $options|count > 0}
        {foreach from=$options item=name key=k}
        
        {if $name|is_array}
            <option value="{$name.id|htmlspecialchars}"
            {if $value == $name.id} selected="selected"{/if}
            >
        {else}
            <option value="{$k|htmlspecialchars}"
            {if $value == $k} selected="selected"{/if}
            >
        {/if}
        {if $name|is_array}
            {$name.name}
        {else}
            {$name}
        {/if}
        
        </option>
        {/foreach}
    {/if}
    </select>
{/if}

{if $type == 'file'}
    <input type="file" class="form-control btn btn-raised" name="{$name}" id="{$name}" value="" data-field="{$name}">
    <input type="text" readonly class="form-control" placeholder="{if isset($placeholder)}{$placeholder}{/if}">
{/if}

{if $type == 'date'}
    <input type="text" class="form-control datepicker" name="{$name}"  value="{$value|date_format:"Y-m-d"}" placeholder="{$placeholder}" data-field="{$name}">
{/if}

    {if isset($addon)}
    </div>
    {/if}
</div>
{/strip}