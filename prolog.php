<?php
CModule::IncludeModule('ws.tests');
define("ADMIN_MODULE_NAME", \WS\Tests\Module::getName());
CJSCore::Init(array('window', 'jquery', 'dialog'));
