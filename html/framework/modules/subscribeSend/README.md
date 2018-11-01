**Модуль Subscribe Send**

Реализована отправка по двум моделям. Event и News. Чтобы запустить рассылку, в консоли необходимо набрать:

```php
php yii subscribeSend/subscribe-send PARAM_TIME PARAM_MODEL
```

Где ```PARAM_TIME``` принимает значения:

```php
0 - Рассылка ежедневно, 1 - Рассылка раз в 3 дня, 2 - Рассылка раз в неделю
```
Где ```PARAM_MODEL``` принимает значения:

```php
news или event
```

Должно получиться что-то вроде этого

```php
php yii subscribeSend/subscribe-send 0 news
```