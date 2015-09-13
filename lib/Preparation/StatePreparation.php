<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

abstract class StatePreparation {

    public function __construct($config, $data = null) {}

    abstract public function commit();

    abstract public function rollback();

    abstract public function valid();

    abstract public function getRender();

    abstract public function name();
}