<?php

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;

if ( !check_bitrix_sessid() ) {
    return;
}

Loc::loadMessages( __FILE__ );

if ( $exception = $APPLICATION->GetException() ) {
    CAdminMessage::ShowMessage( [
        'TYPE' => 'ERROR',
        'MESSAGE' => Loc::getMessage( 'MOD_UNINST_ERR' ),
        'DETAILS' => $exception->GetString(),
        'HTML' => true
    ] );
} else {
    CAdminMessage::ShowNote( Loc::getMessage( 'MOD_UNINST_OK' ) );
}
?>

<form action="<?= $APPLICATION->GetCurPage() ?>">
    <input type="hidden" name="lang" value="<?= Context::getCurrent()->getLanguage() ?>">
    <input type="submit" value="<?= Loc::getMessage( 'MOD_BACK' ) ?>">
</form>
