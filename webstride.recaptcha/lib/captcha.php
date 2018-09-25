<?php

namespace Webstride\Recaptcha;

use Bitrix\Main\Config\Option;

class Captcha
{
    const MODULE_ID = 'webstride.recaptcha';

    /**
     * Проверка капчи.
     *
     * @param string $response
     * @return bool
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function verify( $response )
    {
        $result = self::query( $response );
        if ( is_null( $result ) || !isset( $result->hostname ) || !self::checkHost( $result->hostname ) ) {
            return false;
        }

        return $result->success;
    }

    /**
     * Запрос к сервису Google ReCaptcha.
     *
     * @param string $response
     * @return array|null
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    private static function query( $response )
    {
        if ( empty( $response ) ) {
            // Если нет данных, значит либо не выбрали галочку "Я не робот", либо проблема с сервисом.
            // Дальше продолжать нет смысла.
            return null;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $query = $url . '?secret=' . self::getSecretKey() . '&response=' . $response;

        $response = json_decode( file_get_contents( $query ) );
        if ( json_last_error() === JSON_ERROR_NONE ) {
            return $response;
        }

        return null;
    }

    /**
     * Проверка хоста.
     *
     * @param string $host
     * @return bool
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    private static function checkHost( $host )
    {
        return ( $host === self::getHostName() );
    }

    /**
     * Получить публичный ключ из настроек модуля.
     *
     * @return string
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function getPublicKey()
    {
        return Option::get( self::MODULE_ID, 'public-key' );
    }

    /**
     * Получить секретный ключ из настроек модуля.
     *
     * @return string
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    private static function getSecretKey()
    {
        return Option::get( self::MODULE_ID, 'secret-key' );
    }

    /**
     * Получить хост из настроек модуля.
     *
     * @return string
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    private static function getHostName()
    {
        return Option::get( self::MODULE_ID, 'host' );
    }
}