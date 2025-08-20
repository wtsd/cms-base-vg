      <h4>Характеристики товара</h4>
      <fieldset>
        {foreach from=$specs item=spec}
          <div class="single-spec">
            <label for="spec_{$spec.id}">{$spec.name}</label>
            {if $spec.stype == 0}
              <input type="text" name="spec_{$spec.id}" value="{if isset($row[$spec.id])}{$row[$spec.id].val}{/if}">
              <small>(текстовое значение)</small>
            {elseif $spec.stype == 1}
              <input type="text" name="spec_{$spec.id}" value="{if isset($row[$spec.id])}{$row[$spec.id].val}{/if}">
              <small>(числовое значение)</small>
            {elseif $spec.stype == 2}
              <input type="checkbox" name="spec_{$spec.id}" value="">
            {elseif $spec.stype == 3}
              <select name="spec_{$spec.id}">
                <option>---</option>
                {foreach from=$spec.values key=id item=caption}
                <option value="{$id}"{if isset($row[$spec.id]) && $row[$spec.id].val == $id} selected="selected"{/if}>{$caption}</option>
                {/foreach}
              </select>
            {elseif $spec.stype == 4}
            <input type="color" name="spec_{$spec.id}" value="{if isset($row[$spec.id])}{$row[$spec.id].val}{/if}" list="colors_{$spec.id}" pattern="^#([A-Fa-f0-9]{6})$">
            <datalist id="colors_{$spec.id}">
              {if isset($spec.values) && count($spec.values) > 0}
                {foreach from=$spec.values item=value}
                <option>{$value}</option>
                {/foreach}
              {/if}
            </datalist>
            <div class="color-samples">
              {if isset($spec.values) && count($spec.values) > 0}
                {foreach from=$spec.values item=value}
                <a class="color-val" data-val="{$value}" style="background:{$value};" data-spec="spec_{$spec.id}"></a>
                {/foreach}
              {/if}
            </div>
            {/if}
          </div>
        {/foreach}
      </fieldset>