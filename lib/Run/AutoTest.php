<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tests\Run;

abstract class AutoTest extends Test {

    static protected function generateSentence($word, $extra = array()) {
        $extra = (array) $extra;
        $words = explode(' ', preg_replace('/([A-Z])/', ' $1', str_replace($extra, '', $word)));
        array_walk($words, function (& $word) {
            $word = ucfirst($word);
        });
        return implode(' ', $words);
    }

    public function getName() {
        $class = get_class($this);
        return self::generateSentence($class, 'Test');
    }

    public function getTestName($methodName) {
        return '[А]'.$this->getName().'. '.self::generateSentence($methodName, 'test');
    }
}