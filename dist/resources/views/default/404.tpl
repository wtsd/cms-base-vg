<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
  	<meta name=viewport content="initial-scale=1, minimum-scale=1, width=device-width">
  	<title>Error 404 (Not Found)!!1</title>
    <style>
    body {
        background: #fff;
        margin: 0;
        padding: 0;
    }

    .message {
        display: block;
        margin: 30px auto;
        width: 300px;
    }
    </style>
</head>
<body>
	<div class="message">
	  <h3>404. <em>Ошибка! Страница не найдена</em>.</h3>
	  <p><a href="/">На главную</a></p>
	  {if isset($url)}
	  <p>Ссылка <code>{$url|htmlspecialchars}</code> не найдена на нашем сервере. Извините!</p>
	  {/if}
  	</div>
</body>
</html>