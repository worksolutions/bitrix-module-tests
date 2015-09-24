<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tests\Run;

use WS\Tests\Exceptions\Exception;

class Runner {

    /**
     * @var int
     */
    private $runningId;

    public function createRunning($testsIds, $userId = null) {
    }

    public function useRunning($runningId) {
        $this->runningId = $runningId;
    }

    /**
     * @return int
     */
    public function getRunningId() {
        return $this->runningId;
    }

    public function run() {
        if (!$this->runningId) {
            throw new Exception('Running not determined');
        }
    }

    public function existsWaitedTest() {
    }

    /**
     * @return Test
     */
    public function getWaitedTest() {
    }

    /**
     * @param $reportId
     * @return Test
     */
    public function getTest($reportId) {
    }

    public function getTests() {
        $res = array();
        
    }
}