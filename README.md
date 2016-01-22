# VK-check-install
Скрипт проверяет пользователей установивших Ваше приложение Вконтакте

За один запуск скрипт обходит до 6К пользователей.

Счетчик устанавливается только в том случае, если пользователь добавил приложение в левое меню со страницы приложения, списка приложений или настроек.

НАСТРОЙКА
-------------------
Структура таблицы `counter`
~~~
CREATE TABLE IF NOT EXISTS `counter` (
  `id` int(11) NOT NULL,
  `add` int(11) NOT NULL,
  `err` int(11) NOT NULL,
  `start_id` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
~~~
Дамп
~~~
INSERT INTO `counter` (`id`, `add`, `err`, `start_id`, `start_time`) VALUES
(1, 0, 0, 0, 0);
~~~
в cron.php
```php
<?php
// ...
  $counter = new setCounter(
    array(
    		'setCounter'      => 3,
    		'countRounds'     => 30,
    		'intervalRounds'  => 10000, // 0.01 * 1000000 int micro_seconds
    		'period'          => 86400 	// 1 * 24 * 60 * 60  
    ),
    true
  );
  $counter -> start();
```

