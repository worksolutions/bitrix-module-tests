<?php

namespace WS\Tests;

/**
 * Class Module (Singleton)
 *
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */
class Module {

    private $localizePath = null,
            $localizations = array();

    private static $name = 'ws.tests';

    private function __construct() {
        $this->localizePath = __DIR__.'/../lang/'.LANGUAGE_ID;

        if (!file_exists($this->localizePath)) {
            $this->localizePath = __DIR__.'/../lang/ru';
        }
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
                throw new \Exception('localization by path not found');
            }
            $this->localizations[$path] = new Localization(include $realPath);
        }
        return $this->localizations[$path];
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
