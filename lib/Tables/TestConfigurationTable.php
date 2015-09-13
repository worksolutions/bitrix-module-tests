<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tests\Tables;

class TestConfigurationTable extends BaseTable {

    public static function getTableName() {
        return 'ws_tests_test';
    }

    public static function getMap() {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true
            ),
            'NAME' => array(
                'data_type' => 'string',
                'required' => true,
            ),
            'DESCRIPTION' => array(
                'data_type' => 'string',
            ),
            'LABELS' => array(
                'data_type' => 'string',
            ),
            'PREPARATION' => array(
                'data_type' => 'string',
            ),
            'PREPARATION_DATA' => array(
                'data_type' => 'string',
            ),
            'ALGORITHM' => array(
                'data_type' => 'string',
                'required' => true,
            ),
            'RESULT' => array(
                'data_type' => 'string',
            )
        );
    }
}