<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tests\Run;

class Test {

    public static function className() {
        return get_called_class();
    }

    public function getLabels() {
        return array();
    }

    public function run() {
    }
}