<?php
namespace WS\Tests;
/**
 * @property string $selenium
 *
 * @author <sokolovsky@worksolutions.ru>
 */
final class ModuleOptions {
    private $moduleName = 'ws.tests';

    private $memory = array();

    private function __construct() {}

    /**
     * @staticvar self $self
     * @return Options
     */
    static public function getInstance() {
        static $self = null;
        if (!$self) {
            $self = new self;
        }
        return $self;
    }

    private function _setToDb($name, $value) {
        \COption::SetOptionString($this->moduleName, $name, json_encode($value));
    }

    private function _getFromDb($name) {
        $value = \COption::GetOptionString($this->moduleName, $name);
        return json_decode($value, true);
    }

    public function __set($name, $value) {
        $this->_setToCache($name, $value);
        $this->_setToDb($name, $value);
        return $value;
    }

    public function __get($name) {
        $value = $this->_getFormCache($name);
        if (is_null($value)) {
            $value = $this->_getFromDb($name);
            $this->_setToCache($name, $value);
        }
        return $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    private function _getFormCache($name) {
        return $this->memory[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    private function _setToCache($name, $value) {
        $this->memory[$name] = $value;
    }
}
