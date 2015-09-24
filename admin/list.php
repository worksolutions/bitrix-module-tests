<?php

global $USER, $APPLICATION;

/** @var \Domain\Utils\AdminLink $link */
$link;

/** @var \WS\Tests\Localization $localization */
$localization;

$APPLICATION->SetTitle($localization->message('title'));

$module = \WS\Tests\Module::getInstance();

$app = \Bitrix\Main\Application::getInstance();
$request = $app->getContext()->getRequest();

$sTableID = str_replace(array('\\', '.', ':', '/'), '_', __FILE__);

$lAdmin = new CAdminList($sTableID);

$FilterArr = array(
    "find_type",
    "find_labels",
    "find_name"
);

$tmpFilter = $lAdmin->InitFilter($FilterArr);

foreach ($tmpFilter as $f => $val) {
    global $$f;
    $$f = $val;
}

$arHeaders = array(
    array("id" => "name", "content" => $localization->message('columns.name'), "default" => true, 'sort' => 'name'),
    array("id" => "labels", "content" => $localization->message('columns.labels'), "default" => true),
    array("id" => "run", "content" => $localization->message('columns.run'), "default" => true),
);

$lAdmin->AddHeaders($arHeaders);
$contextMenu = array(
    array(
        "ICON" => "btn_new",
        "TEXT" => $localization->message('actions.add'),
        "TITLE" => $localization->message('actions.add'),
        "LINK" => "ws_tests.php?q=edit"
    )
);
$lAdmin->AddAdminContextMenu($contextMenu);
$module->getTests();
$tests = array();

$tmpFilter['find_name'] = strtolower($tmpFilter['find_name']);
$tmpFilter['find_labels'] = strtolower($tmpFilter['find_labels']);

foreach ($module->getTests() as $id => $test) {
    if ($tmpFilter['find_type'] && $test['type'] != $tmpFilter['find_type']) {
        continue;
    }

    if ($tmpFilter['find_labels']) {
        $hasLabel = false;
        $testLabels = $test['labels'] ?: array();
        foreach ($testLabels as $label) {
            if (strpos($label, $tmpFilter['find_labels']) !== false) {
                $hasLabel = true;
                break;
            }
        }
        if (!$hasLabel) {
            continue;
        }
    }
    if ($tmpFilter['find_name'] && strpos(strtolower($test['name']), $tmpFilter['find_name']) === false) {
        continue;
    }
    $tests[$id] = $test;
}

foreach ($tests as $id => $test) {
    $row = $lAdmin->AddRow($id, $test);

    $row->AddViewField('labels', implode('<br/>', $test['labels'] ?: array()));

    $actions = array(
        array(
            'TEXT' => $localization->message('actions.run'),
            'ACTION' => ''
        ),
        array(
            'TEXT' => $localization->message('actions.history'),
            'ACTION' => ''
        ),
        array(
            'TEXT' => $localization->message('actions.report'),
            'ACTION' => ''
        )
    );

    $test['type'] == 'manual' && $actions[] = array(
        'TEXT' => $localization->message('actions.edit'),
        'LINK' => 'ws_tests.php?q=edit&id='.$id
    );
    $row->AddActions($actions);
}
$lAdmin->AddGroupActionTable(array(
    'run' => $localization->message('actions.run'),
    'download' => $localization->message('actions.download'),
));

$lAdmin->CheckListMode();

if ($_REQUEST["mode"] == "list") {
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
}

$oFilter = new CAdminFilter(
    $sTableID."_filter",
    array(
        $localization->message('columns.name'),
        $localization->message('columns.type'),
        $localization->message('columns.labels'),
    )
);
?>
    <form name="find_form" method="get" action="<?php echo $APPLICATION->GetCurPage() . '?p=list&lang=' . LANGUAGE_ID ;?>">
        <?php
        $oFilter->Begin();
        ?>
        <tr>
            <td><?php echo $localization->message('columns.name')?>:</td>
            <td>
                <input type="text" name="find_name"/>
            </td>
        </tr>
        <tr>
            <td><?php echo $localization->message('columns.type')?>:</td>
            <td>
                <input type="text" name="find_type"/>
            </td>
        </tr>
        <tr>
            <td><?php echo $localization->message('columns.labels')?>:</td>
            <td>
                <input type="text" name="find_labels"/>
            </td>
        </tr>

        <?php
        $oFilter->Buttons(array("table_id"=>$sTableID, "url"=>$APPLICATION->GetCurPage(). '?q=list&lang=' . LANGUAGE_ID , "form"=>"find_form"));
        $oFilter->End();
        ?>
    </form>
<?php
$lAdmin->DisplayList();

if ($_REQUEST["mode"] == "list")  {
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
}
