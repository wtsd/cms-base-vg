{extends file="index.tpl"}

{*block name="title"}{$page_title}{/block*}

{block name="content-wrapper"}
<div class="event-registration normal-block">
    <h2>Регистрация на квест «{$event.name}»</h2>
    <form name="frm-quest-submit" action="#" method="post">
        <div class="form-input">
            <label for="date">Дата и время</label>
            {$date} {$time}
            <input type="hidden" name="date" value="{$date}">
            <input type="hidden" name="slot_id" value="{$slot_id}">
        </div>
        <div class="form-input">
            <label for="event-id">Квест</label>
            {$event.name}
            <input type="hidden" name="event-id" value="{$event.id}">
        </div>
        <div class="form-input">
            <label for="price">Цена</label>
            {$price} руб.
        </div>

        <div class="form-input">
            <label for="name" required="required">Имя</label>
            <input type="text" name="name" value="">
        </div>
        <div class="form-input">
            <label for="tel">Телефон</label>
            <input type="text" name="tel" value="" placeholder="8 (912) 345-67-89" required="required">
        </div>
        <div class="form-input">
            <label for="participants">Участники</label>
            <select name="participants">
                <option value="1">1 игрок</option>
                <option value="2">2 игрока</option>
                <option value="3">3 игрока</option>
                <option value="4">4 игрока</option>
                <option value="5">5 игроков</option>
                <option value="6">6 игроков</option>
            </select>
        </div>
        <div class="form-input">
            <label for="comment">Пожелания</label>
            <textarea name="comment"></textarea>
        </div>
        <div class="form-input">
            <label for="email">Email</label>
            <input type="email" name="email" value="" required="required">
        </div>

        <div class="form-input">
            <button name="doSave" class="btn-submit">Зарегистрироваться</button>
        </div>
    </form>
    <div class="explain">
        <h4>Правила</h4>
        <p>Очень важный текст про разные правила бронирования, цену, уведомления и так далее.</p>
    </div>
</div>
{/block}