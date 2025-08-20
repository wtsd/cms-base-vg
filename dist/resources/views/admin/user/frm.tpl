<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="typeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form name="{$frm_name}" action="{$action}" method="post" data-form="add" data-type="users">
      <input type="hidden" name="id" value="{$id|htmlspecialchars}" data-field="id">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="addModalLabel">Добавить пользователя</h4>
        </div>
        <div class="modal-body">


          {include file="includes/form-group.tpl"
            type="email"
            label="Email"
            value=$obj.email
            name="email"
            placeholder="user@domain.tld"
          }

          {include file="includes/form-group.tpl"
            type="password"
            label="Пароль"
            name="passwd"
          }

          {include file="includes/form-group.tpl"
            type="text"
            label="Имя"
            value=$obj.name
            name="name"
            placeholder="Имя"
          }


          {include file="includes/form-group.tpl"
            type="text"
            label="Телефон"
            value=$obj.tel
            name="tel"
            placeholder="+7 012 345 67 89"
          }

          {include file="includes/form-group.tpl"
            type="textarea"
            label="Описание"
            value=$obj.descr
            name="descr"
            placeholder="Несколько слов о пользователе…"
          }

          {include file="includes/form-group.tpl"
            type="select"
            label="Статус"
            options=[1 => 'Активная', 0 => 'Заблокирована']
            value=$obj.status
            name="status"
          }

          {include file="includes/form-group.tpl"
            type="select"
            label="Группа"
            options=$groups
            value=$obj.group_id
            name="group_id"
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