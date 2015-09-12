<?
use Bitrix\Main\Application;

include __DIR__."/../include.php";

class ws_tests extends CModule {
    const MODULE_ID = 'ws.tests';
    var $MODULE_ID = 'ws.tests';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $PARTNER_NAME = 'WorkSolutions';
    var $PARTNER_URI = 'http://worksolutions.ru';
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $strError = '';

    function __construct() {
        $arModuleVersion = array();
        include(dirname(__FILE__) . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $localization = \WS\Tests\Module::getInstance()->getLocalization('info');
        $this->MODULE_NAME = $localization->message("name");
        $this->MODULE_DESCRIPTION = $localization->message("description");
        $this->PARTNER_NAME = GetMessage('PARTNER_NAME');
        $this->PARTNER_NAME = $localization->message("partner.name");
        $this->PARTNER_URI = 'http://worksolutions.ru';
    }

    function InstallDB($arParams = array()) {
        global $DB;
        $DB->RunSQLBatch(Application::getDocumentRoot().'/'.Application::getPersonalRoot() . "/modules/".$this->MODULE_ID."/install/db/install.sql");
        return true;
    }

    function UnInstallDB($arParams = array()) {
        global $DB;
        $DB->RunSQLBatch(Application::getDocumentRoot().'/'.Application::getPersonalRoot()."/modules/".$this->MODULE_ID."/install/db/uninstall.sql");
        return true;
    }

    function InstallFiles() {
        $rootDir = Application::getDocumentRoot().'/'.Application::getPersonalRoot();
        $adminGatewayFiles = array(
            '/admin/ws_tests.php',
            '/admin/ws_tests_cli.php'
        );
        foreach ($adminGatewayFiles as $adminGatewayFile) {
            copy(__DIR__. $adminGatewayFile, $rootDir . $adminGatewayFile);
        }
        return true;
    }

    function UnInstallFiles() {
        $rootDir = Application::getDocumentRoot().'/'.Application::getPersonalRoot();
        $adminGatewayFiles = array(
            '/admin/ws_tests.php',
            '/admin/ws_tests_cli.php'
        );
        foreach ($adminGatewayFiles as $adminGatewayFile) {
            unlink($rootDir . $adminGatewayFile);
        }
        return true;
    }

    function DoInstall() {
        global $APPLICATION, $data;
        $loc = \WS\Tests\Module::getInstance()->getLocalization('install');
        $options = \WS\Tests\Module::getInstance()->getOptions();
        global $errors;
        $errors = array();
        if ($data['selenium']) {
            $options->selenium = array(
                'url' => $data['selenium']['url']
            );
            $this->InstallFiles();
            $this->InstallDB();
            RegisterModule(self::MODULE_ID);
            \Bitrix\Main\Loader::includeModule(self::MODULE_ID);
        }
        if (!$data || $errors) {
            $APPLICATION->IncludeAdminFile($loc->message('title'), __DIR__.'/form.php');
            return;
        }
    }

    function DoUninstall() {
        global $APPLICATION, $data;
        global $errors;
        $errors = array();
        $loc = \WS\Tests\Module::getInstance()->getLocalization('uninstall');

        if (!$data || $errors) {
            $APPLICATION->IncludeAdminFile($loc->message('title'), __DIR__.'/uninstall.php');
            return;
        }
        if ($data['removeAll'] == "Y") {
            $this->UnInstallDB();
            $this->removeOptions();
        }
        $this->UnInstallFiles();
        UnRegisterModule(self::MODULE_ID);
    }

    private function removeOptions() {
        COption::RemoveOption("ws.tests");
    }
}
