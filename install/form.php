<?php
global $APPLICATION, $errors;
$localization = \WS\Tests\Module::getInstance()->getLocalization('install');
$options = \WS\Tests\Module::getInstance()->getOptions();
$form = new CAdminForm('ew', array(
    array(
        'DIV' => 't1',
        'TAB' => $localization->message('title'),
    )
));
if (!$isOptionsInterface) {
    echo BeginNote();
    echo $localization->message('description');
    echo EndNote();
}
$errors && ShowError(implode(', ', $errors));
$form->Begin(array(
    'FORM_ACTION' => $APPLICATION->GetCurUri()
));
$form->BeginNextFormTab();
$form->AddSection('selenium', $localization->message('fields.selenium.title'));
$form->AddEditField(
    'data[selenium][url]',
    $localization->message('fields.selenium.url'),
    true,
    array(
        'size' => 55
    ),
    $options->selenium['url'] ?: ($isOptionsInterface ? '' : 'http://127.0.0.1:4444/wd/hub/')
);
$form->Buttons(array('btnSave' => false, 'btnApply' => true));
$form->Show();