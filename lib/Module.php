<?php

namespace WS\Tests;

use Bitrix\Main\Event;
use WS\Tests\Run\AutoTest;
use WS\Tests\Run\Runner;
use WS\Tests\Tables\TestConfigurationTable;

/**
 * Class Module (Singleton)
 *
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */
class Module {

    private $localizePath = null,
            $localizations = array();

    private static $name = 'ws.tests';

    /**
     * @var Runner
     */
    private $runner;

    private function __construct() {
        $this->localizePath = __DIR__.'/../lang/'.LANGUAGE_ID;

        if (!file_exists($this->localizePath)) {
            $this->localizePath = __DIR__.'/../lang/ru';
        }
        $this->runner = new Runner();
    }

    static public function getName($stripDots = false) {
        $res = static::$name;
        if ($stripDots) {
            $res = str_replace('.', '_', $res);
        }
        return $res;
    }

    /**
     * @return ModuleOptions
     */
    static public function getOptions() {
        return ModuleOptions::getInstance();
    }

    /**
     * @return Module
     */
    static public function getInstance() {
        static $self = null;
        if (!$self) {
            $self = new self;
        }
        return $self;
    }

    /**
     * @param $path
     * @throws \Exception
     * @return Localization
     */
    public function getLocalization($path) {
        if (!isset($this->localizations[$path])) {
            $realPath = $this->localizePath.'/'.str_replace('.', '/',$path).'.php';
            if (!  file_exists($realPath)) {
                throw new \Exception('localization by path `'.$realPath.'` not found');
            }
            $this->localizations[$path] = new Localization(include $realPath);
        }
        return $this->localizations[$path];
    }

    /**
     * @return Runner
     */
    public function getRunner() {
        return $this->runner;
    }

    /**
     * @return array
     */
    public function getAutotestClasses() {
        $register = new Register();
        $event = new Event(self::$name, 'registration', array($register));
        $event->send();
        return $register->getClasses();
    }

    /**
     * @return array
     */
    public function getTests() {
        $res = array();

        foreach (TestConfigurationTable::getList()->fetchAll() as $manualConfig) {
            $res[$manualConfig['ID']] = array(
                'name' => '[M] '.$manualConfig['NAME'],
                'labels' => explode(',', $manualConfig['LABELS']),
                'type' => 'manual'
            );
        }

        foreach ($this->getAutotestClasses() as $class) {
            if (!class_exists($class) || !is_subclass_of($class, AutoTest::className())) {
                continue;
            }
            $refClass = new \ReflectionClass($class);
            $tests = array_filter($refClass->getMethods(), function (\ReflectionMethod $method) {
                return strpos($method->getName(), 'test') === 0;
            });

            /** @var \ReflectionMethod $test */
            foreach ($tests as $test) {
                /** @var AutoTest $suite */
                $suite = new $class;
                $res[$class.'::'.$test->getName()] = array(
                    'name' => $suite->getTestName($test->getName()),
                    'type' => 'auto',
                    'labels' => $suite->getLabels()
                );
            }
        }
        return $res;
    }
}

function jsonToArray($json) {
    global $APPLICATION;
    $value = json_decode($json, true);
    $value = $APPLICATION->ConvertCharsetArray($value, "UTF-8", LANG_CHARSET);
    return $value;
}

function arrayToJson($data) {
    global $APPLICATION;
    $data = $APPLICATION->ConvertCharsetArray($data, LANG_CHARSET, "UTF-8");
    return json_encode($data);
}
