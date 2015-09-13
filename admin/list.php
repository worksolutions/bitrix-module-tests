<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

$res = \WS\Tests\Tables\TestConfigurationTable::getList();
var_dump($res->getSelectedRowsCount());