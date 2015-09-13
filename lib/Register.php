<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tests;

use WS\Tests\Exceptions\Exception;
use WS\Tests\Resources\BaseResource;

class Register {
    /**
     * @var array
     */
    private $classes = array();

    /**
     * @var BaseResource[]
     */
    private $resources = array();

    public function useTestFolder($path) {
        if (!file_exists($path)) {
            throw new Exception("Test folder not exists");
        }
        $iterator = new \DirectoryIterator($path);
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                continue;
            }
            if ($file->getExtension() != 'php') {
                continue;
            }
            include $file->getPath();
        }

        $autotestBaseSuiteClass = AutoTestSuite::className();
        foreach (get_declared_classes() as $class) {
            if (!is_subclass_of($class, $autotestBaseSuiteClass)) {
                continue;
            }
            $this->classes[] = $class;
        }
        $this->classes = array_unique($this->classes);
    }

    public function useClass($className) {
        $classes = (array) $className;
        foreach ($classes as $class) {
            $this->classes[] = $class;
        }
        $this->classes = array_unique($this->classes);
    }

    public function useResource($name, BaseResource $resource) {
        $this->resources[$name] = $resource;
    }

    public function getClasses() {
        return $this->classes;
    }

    public function getResources() {
        return $this->resources;
    }
}