{extends file="index.tpl"}

{block name="content-wrapper"}

<h2>Шаблон письма</h2>
<ul class="breadcrumb">
  <li><a href="/{$prefix}/">Начало</a></li>
  <li class="active">Редактирование шаблона письма</li>
</ul>

<form enctype="multipart/form-data" action="/{$prefix}/email/save/" method="post" id="frm" role="form" name="frm_email">
  <input type="hidden" name="act" value="ajax" />
  <input type="hidden" name="controller" value="save" />
  <input type="hidden" name="model" value="common\Emailtpl" />
  <input type="hidden" name="c_type_str" value="emailtpl" id="c_type_str" />

  <div class="alert messagebox"></div>
  <div class="frm">

    <div class="form-group-ta">
      <label for="tpl">Шаблон:</label><br>
        <textarea name="tpl" id="tpl" class="form-control" rows="20">{$message}</textarea>
    </div>
    <div class="form-group">
      <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Сохранить</button>
    </div>
  </div>
</form>

{/block}