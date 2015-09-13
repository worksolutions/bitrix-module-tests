<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tests;

abstract class AutoTestSuite {

    /**
     * @return string
     */
    public static function className() {
        return get_called_class();
    }
}