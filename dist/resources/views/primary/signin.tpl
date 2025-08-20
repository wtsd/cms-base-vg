{extends file="index.tpl"}

{block name="title"}Регистрация и вход - {$labels.global_title}{/block}

{block name="content-breadcrumb"}
<div class="row">
  <ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i></a></li>
    <li class="active">{$labels.signin}</li>
  </ul>
</div>
{/block}

{block name="content-wrapper"}
<div class="row normal-block">
    <div class="col col-md-4">
        <h2>Вход</h2>
        <form name="frm_signin" action="/signin/" method="post">
            <div class="form-group">
            <label for="email">Email</label>
              <input type="email" class="form-control" name="email" placeholder="Ваша электронная почта" required value="">
            </div>
            <div class="form-group">
            <label for="password">Пароль</label>
              <input type="password" class="form-control" name="password" placeholder="" required>
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Войти!</button>
            </div>
        </form>
    </div>
    <div class="col col-md-4">
        Условия использования сайта. Короткий и очень важный текст.
    </div>
    <div class="col col-md-4">
        <h2>Регистрация</h2>
        <form name="frm_signup" action="/signup/" method="post">
            <div class="form-group">
            <label for="email">Email</label>
              <input type="email" class="form-control" name="email" placeholder="Ваша электронная почта" required value="">
            </div>
            <div class="form-group">
            <label for="f_name">Полное имя</label>
              <input type="text" class="form-control" name="f_name" placeholder="Ваше имя" required value="">
            </div>
            <div class="form-group">
            <label for="password">Пароль</label>
              <input type="password" class="form-control" name="password" placeholder="" required>
            </div>
            <div class="form-group">
                <button class="btn btn-default">Зарегистрироваться!</button>
            </div>
        </form>

    </div>
</div>
{/block}