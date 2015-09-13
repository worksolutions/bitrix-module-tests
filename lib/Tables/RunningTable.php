<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tests\Tables;

class RunningTable extends BaseTable {

    public static function getTableName() {
        return 'ws_tests_running';
    }

    public static function getMap() {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true
            ),
            'USER_ID' => array(
                'data_type' => 'integer',
            ),
            'TIME' => array(
                'data_type' => 'datetime',
                'autocomplete' => true
            ),
            'DURATION' => array(
                'data_type' => 'integer',
            )
        );
   }
}