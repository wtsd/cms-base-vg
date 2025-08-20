<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="typeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form name="{$frm_name}" action="{$action}" method="post" data-form="add" data-type="users">
      <input type="hidden" name="id" value="{$id|htmlspecialchars}" data-field="id">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="addModalLabel">Добавить филиал</h4>
        </div>
        <div class="modal-body">

        <div class="row">
          <div class="col-md-12">
    
          {include file="includes/form-group.tpl"
            type="text"
            label="Название"
            value=$obj.name
            name="name"
            placeholder="Название филиала"
          }

          {include file="includes/form-group.tpl"
            type="textarea"
            label="Адрес"
            value=$obj.address
            name="address"
            placeholder="ул. Главная, д. 123"
          }

          {include file="includes/form-group.tpl"
            type="text"
            label="Телефон"
            value=$obj.tel
            name="tel"
            placeholder="+7 (901) 234-56-78"
          }
          </div>

          <div class="col-md-6">
            
          {include file="includes/form-group.tpl"
            type="text"
            label="Начало работы"
            value=$obj.opens_at
            name="opens_at"
            placeholder="09:00"
          }

          </div>
          <div class="col-md-6">
            

          {include file="includes/form-group.tpl"
            type="text"
            label="Конец работы"
            value=$obj.closes_at
            name="closes_at"
            placeholder="09:00"
          }
          </div>
          <div class="col-md-12">

          {include file="includes/form-group.tpl"
            type="select"
            label="Статус"
            value=$obj.is_active
            name="is_active"
            options=$statuses
          }

          {include file="includes/form-group.tpl"
            type="select"
            label="Публично"
            value=$obj.is_public
            name="is_public"
            options=['0'=>'Скрыт', '1'=>'Доступен']
          }
                    </div>

          <div class="col-md-6">
            
          {include file="includes/form-group.tpl"
            type="text"
            label="Longitude"
            value=$obj.lng
            name="lng"
          }

          </div>
          <div class="col-md-6">
            

          {include file="includes/form-group.tpl"
            type="text"
            label="Latitude"
            value=$obj.lat
            name="lat"
          }
          </div>
          <div class="col-md-12">

          {include file="includes/form-group.tpl"
            type="textarea"
            label="Комментарий"
            value=$obj.comment
            name="comment"
            placeholder="Закрытый комментарий о филиале"
          }
          </div>

          <div class="col-md-6">
            
          {include file="includes/form-group.tpl"
            type="select"
            label="Страна"
            value=$obj.country
            name="country"
            options=$countries
          }

          </div>
          <div class="col-md-6">
            

          {include file="includes/form-group.tpl"
            type="select"
            label="Город"
            value=$obj.city
            name="city"
            options=$cities
          }
          </div>
          <div class="col-md-12">

          {include file="includes/form-group.tpl"
            type="text"
            label="Почтовый индекс"
            value=$obj.zip
            name="zip"
            placeholder="190123"
          }

          {include file="includes/form-group.tpl"
            type="textarea"
            label="Контактное лицо"
            value=$obj.contact
            name="contact"
            placeholder="Контактное лицо, телефон, скайп, email, VK"
          }
          </div>

        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-default btn-raised" data-dismiss="modal">{$labels.modal.cancel}</a>

          <button type="submit" class="btn btn-primary btn-raised" data-action="save-record">{$labels.modal.save}</button>
        </div>
      </form>
    </div>
  </div>
</div>