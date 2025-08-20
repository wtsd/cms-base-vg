{extends file="index.tpl"}

{block name="title"}{$page_title}{/block}

{block name="content-breadcrumb"}{strip}
<div class="row">
    <ul class="breadcrumb">
      <li><a href="/">{$labels.mainpage}</a></li>
      <li class="active">{$labels.cart.title}</li>
    </ul>
</div>
{/strip}{/block}

{block name="content-wrapper"}{strip}
    <ul class="breadcrumb">
      <li><a href="/">Главная</a></li>
      <li><a href="/cart">Корзина</a></li>
      <li class="active">Оформление заказа</li>
    </ul>


    {if $offers|@count > 0}

      {include file="includes/cart-table.tpl" offers=$offers print=false}

      <p class="pull-right text-right">
      Итого: <strong class="cartSum" data-sumfield="sum">{$sum+$deliveryCost}</strong> руб.
      </p>

    <div class="clearfix"></div>
      <hr>

      <a class="clearCart btn btn-default"><i class="fa fa-times" aria-hidden="true"></i> Очистить</a>
      <a class="btn btn-default" data-toggle="modal" data-target="#myModal"><i class="fa fa-truck" aria-hidden="true"></i> Условия доставки</a>
      <a class="printCart btn btn-default" href="/cart/print/" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> Распечатать</a>
      <a class="btn btn-primary pull-right" data-toggle="modal" data-target=".buy-modal"><strong>Купить!</strong></a>
      <a class="btn btn-default pull-right" data-toggle="modal" data-target=".order-modal"><strong>Заказать!</strong></a>

      <hr />
      <h4>Покупка</h4>
      <p>Чтобы купить выбранный товар в нашем Интернет-магазине, вам нужно:</p>
        <ol>
          <li>Нажать на кнопку «Купить!»</li>
          <li>Заполнить форму с Вашими данными и адресом</li>
          <li>Согласиться с условиями совершения сделки</li>
          <li>Нажать на кнопку «Заказать!»</li>
          <li>Вы будете перенаправлены на страницу оплаты</li>
          <li>Выберите удобный способ оплаты</li>
          <li>По завершении оплаты, наш менеджер получит заказ и свяжется с Вами</li>
        </ol>
    {else}
      <p>Корзина пуста! Добавьте для начала что-нибудь из <a href="/products/">каталога</a> в корзину.</p>

      {include file="includes/special-offers.tpl" products=$recommended}
    {/if}


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Доставка</h4>
              </div>
              <div class="modal-body">
                {$delivery.row.f_text}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
          </div>
        </div>
{/strip}{/block}

{block name="cart-helpers" append}{strip}

<div class="modal fade buy-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" name="frm_order" action="/cart/finish/" method="post" id="frm_order">
        <div class="modal-header">
          Оформление заказа
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="form-group col col-md-6">
              <label for="inputName" class="control-label">Имя <span class="text-danger">*</span></label>
              
                <input type="text" name="name" class="form-control" id="inputName" placeholder="Имя" required="required" value="{if isset($cookie_values.name)}{$cookie_values.name}{/if}">
              
            </div>
            <div class="form-group col col-md-6">
              <label for="inputLastname" class="control-label">Фамилия <span class="text-danger">*</span></label>
              
                <input type="text" name="lastname" class="form-control" id="inputLastname" placeholder="Фамилия" value="{if isset($cookie_values.lastname)}{$cookie_values.lastname}{/if}">
              
            </div>
            <div class="form-group col col-md-6">
              <label for="inputPhone" class="control-label">Телефон <span class="text-danger">*</span></label>
              
                <div class="input-group">
                  <div class="input-group-addon">+7</div>
                  <input type="text" name="phone" id="inputPhone" class="form-control" id="inputPhone" placeholder="(912) 345-67-89" required="required" value="{if isset($cookie_values.phone)}{$cookie_values.phone}{/if}">
                </div>
              
            </div>
            <div class="form-group col col-md-6">
              <label for="inputEmail" class="control-label">Email <span class="text-danger">*</span></label>
              
                <div class="input-group">
                  <div class="input-group-addon">@</div>
                  <input type="email" name="email" id="inputEmail" class="form-control" id="inputEmail" placeholder="Ваша электронная почта" required="required" value="{if isset($cookie_values.email)}{$cookie_values.email}{/if}">
                </div>
            </div>

            <p>На этот адрес электронной почты будет отправлено письмо с подтверждением заказа. Пожалуйста, свяжитесь с нами, если не получите это письмо.</p>
            <div class="clearfix"></div>

            <h3>Адрес</h3>
            <div class="col-md-6">
              <div class="form-group">

                  <div class="input-group">
                    <div class="input-group-addon">Город</div>
                    <input type="text" name="city" class="form-control" id="inputCity" required value="Санкт-Петербург">
                    {*
                    <select  name="city" class="form-control" id="inputCity" required="required">
                      {foreach from=$cities item=city}
                      <option value="{$city}">{$city}</option>
                      {/foreach}
                    </select>
                    *}
                  </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <!-- <label for="inputAddress" class="control-label">Адрес</label> -->
                  <textarea name="address" class="form-control" id="inputAddress" placeholder="Улица, дом, кв">{if isset($cookie_values.address)}{$cookie_values.address}{/if}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="inputComment" class="control-label">Комментарий</label>
              <textarea name="comment" class="form-control" id="inputComment"></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <select name="payment_type" id="payment_type" class="form-control">
              <option value="online">Оплатить через сайт</option>
              <option value="delivery">Оплатить при доставке</option>
              <option value="pickup">Самостоятельно забрать из магазина</option>
            </select>
          </div>
          <div class="col-md-6">

            <div class="form-group">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="i_agree" value="1" required="required"> Я соглашаюсь с <a href="/article/agreement/" target="_blank">условиями</a>
                  </label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <a class="btn btn-default" data-dismiss="modal">Продолжить покупки</a>  
          <button class="btn btn-primary">Заказать!</button>
        </div>
      </form>
    </div>
  </div>
</div>




<div class="modal fade order-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" name="frm_order" action="/cart/placeorder/" method="post" id="frm_order">
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

