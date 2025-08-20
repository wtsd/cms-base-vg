{strip}
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" id="comment-modal-{$record.id}" data-modal-type="significant" data-record_id="{$record.id}">
  <div class="modal-dialog">
    <div class="modal-content">
    <form action="/adm/orders/savecomment/" method="post" name="frm_int_comment">
    <input type="hidden" name="id" value="{$record.id}">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="infoModalLabel-{$record.id}">
        Комментарий к заказу №{$record.id}
        </h4>
      </div>
      <div class="modal-body">
      <label for="int_comment">Внутренний комментарий</label>
      <textarea name="int_comment" id="int_comment" class="form-control">
        {$record.int_comment}
      </textarea>
    
      </div>
      <div class="modal-footer">
        <a type="button" class="btn btn-default btn-raised" data-dismiss="modal">{$labels.modal.close}</a>
        <button class="btn btn-primary">Сохранить</button>
      </div>
    </form>
    </div>
  </div>
</div>
{/strip}