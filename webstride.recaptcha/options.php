<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

$module_id = 'webstride.recaptcha';

$server = Context::getCurrent()->getServer();
$request = Context::getCurrent()->getRequest();

Loc::loadMessages( $server->getDocumentRoot() . BX_ROOT . '/modules/main/options.php' );
Loc::loadMessages( __FILE__ );

if ( $APPLICATION->GetGroupRight( $module_id ) < 'W' ) {
    $APPLICATION->AuthForm( Loc::getMessage( 'ACCESS_DENIED' ) );
}

Loader::includeModule( $module_id );

// Описание опций модуля

$arTabs = [
    [
        'DIV' => 'webstride-module-setting',
        'TAB' => Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_OPTIONS_TAB_SETTING' ),
        'TITLE' => Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_OPTIONS_TAB_SETTING_TITLE' ),
        'OPTIONS' => [
            [
                'host', Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_OPTIONS_HOST' ),
                '',
                [ 'text', 20 ]
            ],
            [
                'secret-key', Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_OPTIONS_SECRET_KEY' ),
                '',
                [ 'text', 50 ]
            ],
            [
                'public-key', Loc::getMessage( 'WEBSTRIDE_RECAPTCHA_OPTIONS_PUBLIC_KEY' ),
                '',
                [ 'text', 50 ]
            ]
        ]
    ],
    [
        'DIV' => 'webstride-module-rights',
        'TAB' => Loc::getMessage( 'MAIN_TAB_RIGHTS' ),
        'TITLE' => Loc::getMessage( 'MAIN_TAB_TITLE_RIGHTS' )
    ]
];

// Сохранение

if( $request->isPost() && $request[ 'Update' ] && check_bitrix_sessid() ) {
    foreach ( $arTabs as $arTab ) {
        foreach ( $arTab[ 'OPTIONS' ] as $arOption ) {
            if ( !is_array( $arOption ) ) {
                // Строка с подсветкой. Используется для разделения настроек на одной вкладке
                continue;
            }

            if ( $arOption[ 'note' ] ) {
                // Уведомление с подсветкой
                continue;
            }

            $optionName = $arOption[ 0 ];
            $optionValue = $request->getPost( $optionName );

            Option::set(
                $module_id,
                $optionName,
                ( is_array( $optionValue ) ? implode( ',', $optionValue ) : $optionValue )
            );
        }
    }
}

// Визуальный вывод опций модуля

$tabControl = new CAdminTabControl( 'tabControl', $arTabs );

$queryData = [
    'mid' => htmlspecialcharsbx( $request[ 'mid' ] ),
    'lang' => $request[ 'lang' ]
];
$action = $APPLICATION->GetCurPage() . '?' . http_build_query( $queryData, '', '&amp;' );

$tabControl->Begin();
?>

    <form action="<?= $action ?>" method="post" name="webstride-recaptcha-setting">

        <?= bitrix_sessid_post() ?>

        <?php
        foreach ( $arTabs as $arTab ) {
            if ( $arTab[ 'OPTIONS' ] ) {
                $tabControl->BeginNextTab();
                __AdmSettingsDrawList( $module_id, $arTab[ 'OPTIONS' ] );
            }
        }

        $tabControl->BeginNextTab();

        require_once( $server->getDocumentRoot() . '/bitrix/modules/main/admin/group_rights.php' );

        $tabControl->Buttons();
        ?>

        <input type="submit" name="Update" value="<?= Loc::getMessage( 'MAIN_SAVE' ) ?>">
        <input type="reset" name="reset" value="<?= Loc::getMessage( 'MAIN_RESET' ) ?>">
    </form>

<?php
$tabControl->End();

?>