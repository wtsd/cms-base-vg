{extends file="index.tpl"}

{block name="title"}{$page_title}{/block}

{block name="content-breadcrumb"}{strip}
<div class="row">
    <ul class="breadcrumb">
      <li><a href="/">Главная</a></li>
      <li class="active">Корзина</li>
    </ul>
</div>
{/strip}{/block}

{block name="content-wrapper"}{strip}


<div class="row normal-block">
    {if $offers|@count > 0}
      <p>Всего товаров: <strong class="cartCount">{$offers|count}</strong></p>
      <p>На сумму: <strong class="cartSum">{$sum}</strong> рублей</p>

      {include file="includes/cart-table.tpl" offers=$offers print=false}

      <button class="clearCart btn btn-default">Очистить</button>
      <a class="printCart btn btn-default" href="/cart/print/" target="_blank">Распечатать</a>
      <a class="btn btn-primary" data-toggle="modal" data-target=".order-modal"><strong>Оформить заказ!</strong></a>

      <hr />
      <p><small>Когда Вы закончите оформление заказа, наш оператор получит сигнал и в самое ближайшее время свяжется с Вами.</small></p>
    {else}
      <p>Корзина пуста! Добавьте для начала что-нибудь из <a href="/products/">каталога</a> в корзину.</p>

      {include file="includes/special-offers.tpl" products=$recommended}
    {/if}
  </div>
{/strip}{/block}

{block name="cart-helpers" append}{strip}

<div class="modal fade order-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" name="frm_order" action="/cart/finish/" method="post" id="frm_order">
        <div class="modal-header">
          Форма для заполнения
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <h3>Личные данные</h3>
            <input type="hidden" name="client_type" value="1">
            <div class="form-group">
              <label for="inputName" class="col-sm-4 control-label">Имя <span class="required">*</span></label>
              <div class="col-sm-7">
                <input type="text" name="name" class="form-control" id="inputName" placeholder="Имя" required="required" value="{if isset($cookie_values.name)}{$cookie_values.name}{/if}">
              </div>
            </div>
            <div class="form-group">
              <label for="inputLastname" class="col-sm-4 control-label">Фамилия </label>
              <div class="col-sm-7">
                <input type="text" name="lastname" class="form-control" id="inputLastname" placeholder="Фамилия" value="{if isset($cookie_values.lastname)}{$cookie_values.lastname}{/if}">
              </div>
            </div>
            <div class="form-group">

              <label for="inputPhone" class="col-sm-4 control-label">Телефон</label>
              <div class="col-sm-7">
                <div class="input-group">
                  <div class="input-group-addon">+7</div>
                  <input type="text" name="phone" id="inputPhone" class="form-control" id="inputPhone" placeholder="(912) 345-67-89" required="required" value="{if isset($cookie_values.phone)}{$cookie_values.phone}{/if}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail" class="col-sm-4 control-label">Email</label>
              <div class="col-sm-7">
                <div class="input-group">
                  <div class="input-group-addon">@</div>
                  <input type="email" name="email" id="inputEmail" class="form-control" id="inputEmail" placeholder="Ваша электронная почта" required="required" value="{if isset($cookie_values.email)}{$cookie_values.email}{/if}">
                </div>
              </div>
            </div>

            <h3>Адрес</h3>
            <div class="form-group">
              <label for="inputCity" class="col-sm-4 control-label">Город <span class="required">*</span></label>
              <div class="col-sm-8">
                <select  name="city" class="form-control" id="inputCity" required="required">
                  {foreach from=$cities item=city}
                  <option value="{$city}">{$city}</option>
                  {/foreach}
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="inputAddress" class="col-sm-4 control-label">Адрес</label>
              <div class="col-sm-8">
                <textarea name="address" class="form-control" id="inputAddress">{if isset($cookie_values.address)}{$cookie_values.address}{/if}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="inputComment" class="col-sm-4 control-label">Комментарий</label>
              <div class="col-sm-8">
                <textarea name="comment" class="form-control" id="inputComment"></textarea>
              </div>
            </div>

            <h3>Оплата</h3>
            <div class="form-group">
              <label for="inputPayment" class="col-sm-4 control-label">Форма оплаты <span class="required">*</span></label>
              <div class="col-sm-4">
                  <select name="payment_type" required="required" id="inputPayment">
                      <option value="1">Наличные курьеру</option>
                      <option value="2">Банковская карта с курьером</option>
                      {*<!--option value="3">Банковская карта через сайт</option>
                      <option value="4">Яндекс.Деньги</option>
                      <option value="5">WebMoney</option-->*}
                  </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="i_agree" value="1" required="required"> Я соглашаюсь с <a href="/article/agreement/" target="_blank">условиями</a>
                  </label>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button class="printCart btn btn-default" data-dismiss="modal">Продолжить покупки</button>  
          <button class="btn btn-primary">Заказать!</button>
        </div>
      </form>
    </div>
  </div>
</div>
{/strip}{/block}