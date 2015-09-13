<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tests\Tables;

class TestReportTable extends BaseTable {

    public static function getTableName() {
        return 'ws_tests_test_report';
    }

    public static function getMap() {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true
            ),
            'SUCCESS' => array(
                'data_type' => 'integer',
                'required' => true
            ),
            'TEST_ID' => array(
                'data_type' => 'integer'
            ),
            'STATE' => array(
                'data_type' => 'string'
            ),
            'DURATION' => array(
                'data_type' => 'integer'
            ),
            'MESSAGE' => array(
                'data_type' => 'string'
            ),
            'DATA' => array(
                'data_type' => 'string'
            ),
            'CALL_STACK' => array(
                'data_type' => 'string'
            )
        );
    }
}