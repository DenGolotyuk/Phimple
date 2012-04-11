<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="en" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Что-то не так...</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<body style="background: #222; margin: 0; border: 0; font-family: Tahoma; color: #eee; padding: 5em; line-height: 110%;">

		<? if ( !config::get('production') || ( config::get('production') && ($e instanceof pub_exception) ) ) { ?>
			<h1 style="line-height: 150%; text-shadow: -1px -1px #000; font-size: 2em; color: #fff; font-weight: normal;"><?=$e->getMessage();?></h1>

			<div style="color: greenyellow; font-family: monospace;">
				<? foreach ( $e->getTrace() as $trace ) { ?>
					<p>
						<?=$trace['line']?>: <?=$trace['file']?><br/>
						<?=$trace['class']?><?=$trace['type']?><?=$trace['function']?>();<br />
						Arguments: <?print_r($trace['args'])?>
					</p>
				<? } ?>
			</div>
		<? } else { ?>
			<h1 style="line-height: 150%; text-shadow: -1px -1px #000; font-size: 5em; color: #fff; font-weight: normal;">Что-то не так</h1>

			<div style="color: #ddd; font-family: monospace;">
				Похоже, что Вы нашли у нас ошибку. Мы уже работаем над ее исправлением.
				<br /><br />
				Попробуйте обновить страницу через пару минут.
			</div>
		<? } ?>
	</body>
</html>