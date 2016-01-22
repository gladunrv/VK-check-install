# VK-check-install
Скрипт проверяет пользователей установивших Ваше приложение Вконтакте

За один запуск скрипт проверяет до 12К пользователей.


НАСТРОЙКА
-------------------
Структура таблицы `check_install`
~~~
CREATE TABLE IF NOT EXISTS `check_install` (
  `id` int(11) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `start_id` int(11) NOT NULL DEFAULT '0',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `message` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
~~~
Дамп таблицы
~~~
INSERT INTO `check_install` (`id`, `added`, `start_id`, `start_time`, `message`) VALUES
(1, 0, 0, 0, '');

~~~Структура таблицы `check_users`
~~~
CREATE TABLE IF NOT EXISTS `check_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
~~~

в cron.php
```php
<?php
// ...
	$CheckInstall = new CheckInstall( 
		array(
			'countRounds' => 60,
			'intervalRounds' => 2000
    ),
    true
  );
  $CheckInstall -> start();
```

