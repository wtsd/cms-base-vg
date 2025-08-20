<button class="btn_save btn btn-primary"><span class="glyphicon glyphicon-save"></span> Сохранить</button>
<a class="btn btn-default" href="/{$prefix}/{$c_type}/browse/"><span class="glyphicon glyphicon-th-list"></span> </a>
<a class="btn btn-default" href="/{$prefix}/{$c_type}/add/"><span class="glyphicon glyphicon-pencil"></span> </a>
{if isset($obj.rewrite)}
 <a href="/{$c_type}/{$obj.rewrite|htmlspecialchars}" data-action="preview" target="_blank" class="btn btn-info" {if !isset($obj.rewrite) || $obj.rewrite == ''} style="display:none;"{/if}><span class="glyphicon glyphicon-eye-open"></span> </a>
{/if}