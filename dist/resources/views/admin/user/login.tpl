{extends file="index.tpl"}

{block name="content-wrapper"}
  {if $status == 'success'}
  <script>location.href = '/{$prefix}/';</script>
  {else}

  <div class="row">
      <div class="col col-md-4">

        <div class="alert" style="display:none;">
          <span data-msg="msg"></span>
          <button type="button" class="close" data-dismiss="alert">×</button>
        </div>

        <form role="form" action="/{$prefix}/auth/" method="post" name="frm_auth">

          {include file="includes/form-group.tpl"
            type="email"
            name="email"
            placeholder="user@domain.tld"
            addon="fa fa-envelope"
          }
          {include file="includes/form-group.tpl"
            type="password"
            name="passwd"
            addon="fa fa-lock"
          }

          <button class="btn btn-primary btn-block"><i class="fa fa-key"></i> {$labels.enter}</button>
        </form>
      </div>

      <div class="col col-md-8 login-right">
        <h3>Уведомление</h3>
        <p>Это закрытая часть сайта для администрирования ресурса. При попытке авторизации, Вы соглашаетесь с тем, что имеете право на редактирование информации на основном сайте и понимаете всю ответственность этих действий.</p>
        <p>Вся информация о Вашем входе будет зафиксирована, все действия зажурналированы для Вашей же безопасности</p>
      </div>
  </div>
  {/if}
{/block}
