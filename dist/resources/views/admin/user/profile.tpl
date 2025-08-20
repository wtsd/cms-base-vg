{extends file="index.tpl"}

{block name="content-wrapper"}
<div class="row">
  {if isset($status) && $status == 'saved'}
  <div class="alert alert-success alert-dismissible" role="alert">
     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     Профиль успешно сохранён!
   </div>
  {/if}
  <div class="col-md-3">
    
  </div>
  <div class="col-md-3">
    <h2>Профиль</h2>
    <form name="frm_profile" method="post" action="/{$prefix}/profile/save/" id="frm_profile" role="form" class="form-horizontal">


          {include file="includes/form-group.tpl"
            type="email"
            value=$user->getEmail()
            name="email"
            placeholder="user@domain.tld"
            addon="fa fa-envelope"
          }

          {include file="includes/form-group.tpl"
            type="password"
            name="passwd"
            addon="fa fa-lock"
          }

          {include file="includes/form-group.tpl"
            type="text"
            addon="fa fa-user"
            value=$user->getName()
            name="name"
            placeholder="Имя"
          }

          {include file="includes/form-group.tpl"
            addon="fa fa-whatsapp"
            type="text"
            value=$user->getTel()
            name="tel"
            placeholder="+7 012 345 67 89"
          }

      <div class="form-group">
        <button class="btn btn-primary">Обновить информацию</button>
      </div>
    </form>
    
  </div>
</div>
{/block}