{strip}
<form name="frm_comment" action="/" method="post" class="comment-frm" data-fid="{$fid}" data-type="{$type}">
    <input type="hidden" name="type" value="{$type}">
    <input type="hidden" name="fid" value="{$fid}">
  <div class="form-group">
    <input class="form-control" name="name" placeholder="Имя" required="required">
  </div>
  <div class="form-group">
    <textarea name="comment" class="form-control" placeholder="Комментарий" required="required"></textarea>
  </div>
  <div class="form-group">
    <div class="col col-md-8">
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <div class="g-recaptcha" data-sitekey="{$recaptcha.sitekey}"></div>
    </div>
    <div class="col col-md-4">
        <button class="btn btn-primary addComment">Отправить</button>
    </div>
  </div>
  <div class="form-group">
    
  </div>
</form>
{/strip}