{assign var="frmType" value="groups"}
{assign var="modalTitle" value="Добавить группу"}
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="typeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form name="{$frm_name}" action="{$action}" method="post" data-form="add" data-type="{$frmType}">
      <input type="hidden" name="id" value="{$id|htmlspecialchars}" data-field="id">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="addModalLabel">{$modalTitle}</h4>
        </div>
        <div class="modal-body">

          {include file="includes/form-group.tpl"
            type="text"
            label="Название"
            value=$obj.name
            name="name"
            placeholder="Название"
          }

          {include file="includes/form-group.tpl"
            type="textarea"
            label="Описание"
            value=$obj.comment
            name="comment"
            placeholder="Комментарий о группе"
          }

          {include file="includes/form-group.tpl"
            type="select"
            label="Статус"
            options=[1 => 'Активная', 0 => 'Заблокирована']
            value=$obj.status
            name="status"
          }

        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-default btn-raised" data-dismiss="modal">{$labels.modal.cancel}</a>

          <button type="submit" class="btn btn-primary btn-raised" data-action="save-record">{$labels.modal.save}</button>
        </div>
      </form>
    </div>
  </div>
</div>