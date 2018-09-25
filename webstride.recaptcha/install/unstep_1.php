<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Context;

if ( !check_bitrix_sessid() ) {
    return;
}

Loc::loadMessages( __FILE__ );

?>

<form action="<?= $APPLICATION->GetCurPage() ?>">

    <?= bitrix_sessid_post() ?>

    <input type="hidden" name="lang" value="<?= Context::getCurrent()->getLanguage() ?>">
    <input type="hidden" name="id" value="webstride.recaptcha">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">

    <?php
    CAdminMessage::ShowMessage( [
        'TYPE' => 'ERROR',
        'MESSAGE' => Loc::getMessage( 'MOD_UNINST_WARN' ),
        'HTML' => true
    ] );
    ?>

    <p>
        <?= Loc::getMessage( 'MOD_UNINST_SAVE' ) ?>
    </p>

    <p>
        <input type="checkbox" name="save-data" id="webstride-recaptcha-save-data" checked>
        <label for="webstride-recaptcha-save-data"><?= Loc::getMessage( 'MOD_UNINST_SAVE_TABLES' ) ?></label>
    </p>

    <input type="submit" value="<?= Loc::getMessage( 'MOD_UNINST_DEL' ) ?>">
</form>
