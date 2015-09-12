<?php

global $USER;
if (!$USER->isAdmin()) {
    return array();
}
$loc = \WS\Tests\Module::getInstance()->getLocalization('menu');
$inputUri = '/bitrix/admin/ws_tests.php?q=';
return array(
    array(
        'parent_menu' => 'global_menu_settings',
        'sort' => 500,
        'text' => $loc->message('title'),
        'title' => $loc->message('title'),
        'module_id' => 'ws.tests',
        'icon' => '',
        'items_id' => 'ws_tests_menu',
        'items' => array(
            array(
                'text' => $loc->message('list'),
                'url' => $inputUri.'list',
            ),
            array(
                'text' => $loc->message('reports'),
                'url' => $inputUri.'reports',
            )
        )
    )
);
