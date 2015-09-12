<?php
global $APPLICATION, $errors;
$localization = \WS\Tests\Module::getInstance()->getLocalization('uninstall');
$options = \WS\Tests\Module::getInstance()->getOptions();
$form = new CAdminForm('ew', array(
    array(
        'DIV' => 't1',
        'TAB' => $localization->message('tab'),
    )
));
ShowMessage(array(
    'MESSAGE' => $localization->message('description'),
    'TYPE' => 'OK'
));

$errors && ShowError(implode(', ', $errors));
$form->Begin(array(
    'FORM_ACTION' => $APPLICATION->GetCurUri()
));
$form->BeginNextFormTab();
$form->AddCheckBoxField('data[removeAll]', $localization->message('fields.removeAll'), true, "Y", false);
$form->Buttons(array('btnSave' => false, 'btnApply' => true));
$form->Show();