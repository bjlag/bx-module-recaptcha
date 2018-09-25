<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages( __FILE__ );

class Webstride_Recaptcha extends CModule
{
    /**
     * Webstride_Recaptcha constructor.
     */
    public function __construct()
    {
        $arModuleVersion = [];
        include_once __DIR__ . '/version.php';

        $this->MODULE_ID = 'webstride.recaptcha';
        $this->MODULE_VERSION = $arModuleVersion[ 'VERSION' ];
        $this->MODULE_VERSION_DATE = $arModuleVersion[ 'VERSION_DATE' ];
        $this->MODULE_NAME = Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_MODULE_NAME' );
        $this->MODULE_DESCRIPTION = Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_MODULE_DESCRIPTION' );

        $this->PARTNER_NAME = Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_PARTNER_NAME' );
        $this->PARTNER_URI = Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_PARTNER_URI' );

        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    /**
     * Определяем есть ли у текущего ядра поддержка D7.
     * TRUE - есть, FALSE - нет.
     *
     * @return bool
     */
    private function isVersionD7()
    {
        return CheckVersion( ModuleManager::getVersion( 'main' ), '14.0.0' );
    }

    /**
     * Вернуть путь до модуля.
     * Если передан $notDocRoot как TRUE, то путь будет от корня сайта, а не сервера.
     *
     * @param bool $notDocRoot
     * @return string
     */
    private function getPath( $notDocRoot = false )
    {
        $path = dirname( __DIR__ );
        if ( $notDocRoot ) {
            $path = str_ireplace( Application::getDocumentRoot(), '', $path );
        }

        return $path;
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public function UnInstallDB(  )
    {
        Option::delete( $this->MODULE_ID );
    }

    /**
     * Установка модуля.
     */
    public function DoInstall()
    {
        global $APPLICATION;

        if ( $this->isVersionD7() ) {
            ModuleManager::registerModule( $this->MODULE_ID );
        } else {
            $APPLICATION->ThrowException( Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_ERROR_VERSION' ) );
        }

        $APPLICATION->IncludeAdminFile( Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_INSTALL_TITLE' ),
            $this->getPath() . '/install/step.php' );
    }

    /**
     * Удаление модуля.
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public function DoUninstall()
    {
        global $APPLICATION;

        $request = Context::getCurrent()->getRequest();

        if ( $request[ 'step' ] < 2 ) {
            $APPLICATION->IncludeAdminFile( Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_UNINSTALL_TITLE' ),
                $this->getPath() . '/install/unstep_1.php' );
        } elseif ( $request[ 'step' ] == 2 ) {
            ModuleManager::unRegisterModule( $this->MODULE_ID );

            if ( !$request[ 'save-data' ] ) {
                $this->UnInstallDB();
            }

            $APPLICATION->IncludeAdminFile( Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_UNINSTALL_TITLE' ),
                $this->getPath() . '/install/unstep_2.php' );
        }
    }

    public function GetModuleRightList()
    {
        return [
            'reference_id' => [ 'D', 'W' ],
            'reference' => [
                '[D] ' . Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_RIGHTS_DENIED' ),
                '[W] ' . Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_RIGHTS_WRITE' )
            ]
        ];
    }
}