<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8" />
 <link rel="stylesheet" type="text/css" href="/web/css/upload.css" />
</head>
<body>


<form enctype="multipart/form-data" class="upload-form" action="/{$prefix}/ajax/uploadImage/{$type|htmlspecialchars}/{$id|htmlspecialchars}/">
    <div class="form-input">
      <label for="upload">Загрузить фотографии:</label>
      <input type="file" name="image" multiple class="upload-input"><br>
      <progress style="display:none;"></progress>
    </div>
</form>

{literal}
<script src="/resources/assets/jquery/dist/jquery.min.js" type="text/javascript"></script>
<script src="/web/js/admin.js" type="text/javascript"></script>
{/literal}

</body>
</html>