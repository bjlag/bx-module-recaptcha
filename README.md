# Модуль Bitrix для работы с Google ReCaptcha

Модуль написан на ядре D7, для корректной работы нужна версия ядра не ниже версии 14.0.0.

## Установка
Модуль корректно работает из папки local и bitrix.  
* Скопируйте папку _webstride.recaptcha_ в папку _local/modules_ или _bitrix/modules_.  
* Установите модуль в административном разделе сайта _Marketplace / Установленные решения_.
* Перейдите в настройки модуля _Настройки / Настройки продукта / Настройки модулей / Google ReCaptcha_.
* В настройках модуля укажите хост, секретный и публичный ключи каптчи.

## Использование
Методы модуля доступны в пространстве имен `Webstride\Recaptcha\Captcha`.

```php
/**
 * Проверка капчи.
 *
 * @param string $response - Ответ от сервиса Google ReCaptcha.
 * @return bool - TRUE проверку прошли, FALSE нет.
 */
Webstride\Recaptcha\Captcha::verify( $response );

/**
 * Получить публичный ключ из настроек модуля.
 *
 * @return string
 */
Webstride\Recaptcha\Captcha::getPublicKey();
```
