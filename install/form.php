<?php
global $APPLICATION, $errors;
$localization = \WS\Tests\Module::getInstance()->getLocalization('setup');
$options = \WS\Tests\Module::getInstance()->getOptions();
$form = new CAdminForm('ew', array(
    array(
        'DIV' => 't1',
        'TAB' => $localization->message('tab'),
    )
));
echo BeginNote();
echo $localization->message('description');
echo EndNote();
$errors && ShowError(implode(', ', $errors));
$form->Begin(array(
    'FORM_ACTION' => $APPLICATION->GetCurUri()
));
$form->BeginNextFormTab();
$form->AddEditField('data[catalog]', $localization->message('fields.catalog'), true, array(), $options->catalogPath ?: '/migrations');
$form->Buttons(array('btnSave' => false, 'btnApply' => true));
$form->Show();