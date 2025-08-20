{strip}
<form role="form" name="frm_call" action="/callrequest/" method="post" id="frm_call">
	<div class="container-fluid">
		<div class="row">
	      <p>Хотите, чтобы наш оператор связался с вами по телефону?</p>
	      <p>Это может быть удобнее, чем поиск по каталогу. Напишите Ваше имя и телефон, а наш оператор свяжется с вами</p>
			<div class="form-group">
			  <label for="inputName" class="col-sm-4 control-label">Имя <span class="required">*</span></label>
			  <div class="col-sm-7">
			    <input type="text" name="name" class="form-control" id="inputName" placeholder="Имя" required="required" value="{if isset($cookie_values.name)}{$cookie_values.name}{/if}">
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
		</div>
	</div>
	<button class="btn btn-primary">Заказать звонок</button>
</form>
{/strip}