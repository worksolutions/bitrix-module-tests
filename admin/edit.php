<?php

include(__DIR__.'/../../bitrix/modules/iblock/admin_tools.php');

/** @var CMain $APPLICATION */
/** @var CUser $USER */
/** @var \WS\Tests\Localization $localization */

$aTabs = array(
    array(
        "DIV" => "t1",
        "TAB" => $localization->message('title'),
        "ICON" => "sale",
        "TITLE" => "",
    )
);

$tabControl = new CAdminForm("tabControl", $aTabs);
$errorMessage = "";
$bInitVars = false;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$id = $request->isPost() ? $request->getPost('id') : $request->get('id');

if ($request->isPost() && check_bitrix_sessid()) {
    $errors = '';
    $post = $request->getPostList();
    !$post->get('name') && $errors[] = $localization->message('errors.name-empty');
    !$post->get('case') && $errors[] = $localization->message('errors.scenario-empty');
    !$post->get('result') && $errors[] = $localization->message('errors.result-empty');

    $labels = implode(',', array_filter(array_map(function ($label) {
        return strtolower(trim($label));
    }, $post->get('labels') ?: array())));

    $cases = $post->get('case');
    $algorithm = array();
    foreach ($cases['action'] as $key => $action) {
        $algorithm[$key] = array(
            'action' => $action,
            'result' => $cases['result'][$key]
        );
    }

    $data = array(
        'NAME' => $post->get('name'),
        'LABELS' => $labels,
        'PREPARATION' => $post->get('preparation'),
        'PREPARATION_DATA' => '',
        'ALGORITHM' => json_encode($algorithm),
        'RESULT' => $post->get('result')
    );
    if (!$errors) {
        !$id && $addResult = \WS\Tests\Tables\TestConfigurationTable::add($data);
        $id && \WS\Tests\Tables\TestConfigurationTable::update($id, $data);
        if ($post->get('save')) {
            LocalRedirect('ws_tests.php?q=list');
        }
        !$id && $id = $addResult->getId();
        LocalRedirect('ws_tests.php?q=edit&id='.$id);
    }
}

if ($id && !$data) {
    $data = \WS\Tests\Tables\TestConfigurationTable::getById($id)->fetch();
}

if ($data) {
    $data['ALGORITHM'] = json_decode($data['ALGORITHM'], true);
    $data['LABELS'] = explode(',', $data['LABELS']);

} else {
    $data = array();
}

// view
$APPLICATION->SetTitle($localization->message('title'));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

/*********************************************************************/
/********************  BODY  *****************************************/
/*********************************************************************/
$context = new CAdminContextMenu(array(
    array(
        "TEXT" => $localization->message('context-menu.back'),
        "LINK" => 'ws_tests.php?q=list',
        "ICON" => "btn_list"
    ),
));
$context->Show();

if ($errors) {
    echo CAdminMessage::ShowMessage(array(
            "DETAILS" => implode('<br />', $errors),
            "TYPE"=>"ERROR",
            "MESSAGE"=> $localization->message('errors.message'),
            "HTML"=>true
        )
    );
}

$tabControl->Begin(array(
    'FORM_ACTION' => '',
    'NAME' => 'client'
));
$tabControl->BeginNextFormTab();

$tabControl->BeginCustomField('sessid', '');
?>
    <tr style="display: none;">
        <td colspan="2">
            <input type="hidden" name="id" value="<?=$id?>">
            <?=bitrix_sessid_post()?>
        </td>
    </tr>
<?php
$tabControl->EndCustomField('sessid');
$tabControl->AddEditField('name', $localization->message('messages.name').':', true, array('size' => 50), $data['NAME']);
$tabControl->BeginCustomField('labels', '');
?>
    <tr>
        <td><?=$localization->message('messages.labels').':'?></td>
        <td><?php
            foreach($data['LABELS'] ?: array() as $label):
        ?>
            <input type="text" name="labels[]" style="margin-bottom: 3px;" value="<?=$label?>"/><br />
        <?php
            endforeach;
        ?>
            <input type="text" name="labels[]" style="margin-bottom: 3px;" value=""/>
            <input type="text" class="last" name=""/></td>
    </tr>
<?php
$tabControl->EndCustomField('labels');
$tabControl->AddDropDownField('state', $localization->message('messages.state').':', false, array('' => $localization->message('messages.no')));
$tabControl->BeginCustomField('scenario', '');
?>
    <tr>
        <td style="vertical-align: top;" width="20%;"><b><?=$localization->message('messages.scenario').':'?></b></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2" width="100%">
            <table style="background-color: #FFFFFF; width: 100%; margin: 10px; padding: 10px;">
            <thead>
                <td align="center" width="8%"><?=$localization->message('case.step')?></td>
                <td align="center" width="60%"><?=$localization->message('case.action')?></td>
                <td align="center" width=""><?=$localization->message('case.result')?></td>
                <td align="center" width="5%"></td>
            </thead>
            <tbody>
                <?php
                foreach($data['ALGORITHM']?: array(array()) as $k => $algorithmItem):
                ?>
                <tr>
                    <td align="center"><b><?=$k+1?></b></td>
                    <td align="center"><input name="case[action][]" style="width: 100%" value="<?=$algorithmItem['action']?>"/></td>
                    <td align="center"><input name="case[result][]" style="width: 95%" value="<?=$algorithmItem['result']?>" /></td>
                    <td align="center" style="vertical-align: text-top;">
                        <a href="#" class="delete" style="color: red; text-decoration: none;">[x]</a>&nbsp;
                        <a href="#" class="move-up" style="color: #008000; text-decoration: none;">&#9650;</a>
                    </td>
                </tr>
                <?php
                endforeach;
                ?>
                <tr class="actions">
                    <td align="center"><a href="#" style="text-decoration: none; border-bottom: 1px dashed;" class="add"><?=$localization->message('messages.add')?></a></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
            </table>
        </td>
    </tr>
<?php
$tabControl->EndCustomField('scenario');
$tabControl->BeginCustomField('result', 'result');
?>
    <tr>
        <td style="vertical-align: top;" width="20%;"><b><?=$localization->message('messages.result').':'?></b></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">
            <span style="margin: 10px;"><textarea name="result" style="width: 99%;"><?=$data['RESULT']?></textarea></span>
        </td>
    </tr>
<?php
$tabControl->EndCustomField('result');
$tabControl->Buttons(array('btnSave' => true, 'btnApply' => true));
$tabControl->Show();
CJSCore::Init(array('jquery'));
?>
<script type="text/javascript">
    $('table#t1_edit_table > tbody > tr').find('td:first').attr('width', '20%');

    // labels
    $(function () {
        var subTemplate = '[name="labels[]"]';
        var cell = $(subTemplate+':first').parents('td:first');
        var $prototype =  cell.find(subTemplate+':last').remove();
        var $stub = cell.find("input.last:first");

        var count = function () {
            return list().size();
        };

        var list = function () {
            return cell.find(subTemplate);
        };

        var getLast = function () {
            return cell.find(subTemplate+':last');
        };

        var add = function () {
            var $input = $prototype.clone();
            count() > 0 ? getLast().next().after($input) : $stub.before($input);
            $input.after('<br/>');
            $input.blur(onBlur);
        };
        var remove = function ($item) {
            $item = $($item);
            var $before = $item.next();
            $before.is('br') && $before.remove();
            $item.remove();
        };

        var onFocus = function () {
            (getLast().val() != '' || !count()) && add();
            getLast().focus();
        };

        var onBlur = function () {
            list().each(function () {
                $(this).val() == '' && remove(this);
            });
        };

        $stub.focus(onFocus);
        list().blur(onBlur);
    });

    // scenarios
    $(function () {
        var $table = $('[name="case[action][]"]').parents('tbody:first');
        var step = 0;
        var $protoRow = $table.find('tr:not(.actions):last').clone();
        $protoRow.find(':input').val('')

        var main = {
            rows: {},
            firstTurnOffActions: function () {
                $table.find('tr:not(.actions):first').find('a.move-up').remove();
            },
            turnOnActions: function ($row) {
                var $td = $protoRow.find('td:last').clone();
                $row.find('td:last').replaceWith($td);
                var That = this;
                var step = $row.data('step');
                $row.find('a.delete').click(function (e) {
                    e.preventDefault();
                    That.removeRow($row.data('step'));
                });
                $row.find('a.move-up').click(function (e) {
                    e.preventDefault();
                    That.moveUp($row.data('step'));
                });
            },
            addRow: function () {
                var $insert = $protoRow.clone();
                if ($table.find('tr:not(.actions):last').size()) {
                    $table.find('tr:not(.actions):last').after($insert);
                } else {
                    $table.prepend($insert);
                }
                var currentStep = ++step;
                $insert.find("td:first b").text(currentStep);
                var That = this;
                $insert.data('step', currentStep);
                $insert.find('a.delete').click(function (e) {
                    e.preventDefault();
                    That.removeRow($insert.data('step'));
                });
                $insert.find('a.move-up').click(function (e) {
                    e.preventDefault();
                    That.moveUp($insert.data('step'));
                });
                this.rows[currentStep] = $insert;
                this.firstTurnOffActions();
            },
            initRow: function ($row) {
                var That = this;
                var currentStep = ++step;
                $row.data('step', currentStep);
                $row.find('a.delete').click(function (e) {
                    e.preventDefault();
                    That.removeRow($row.data('step'));
                });
                $row.find('a.move-up').click(function (e) {
                    e.preventDefault();
                    That.moveUp($row.data('step'));
                });
                this.rows[currentStep] = $row;
                this.firstTurnOffActions();
            },
            removeRow: function (rStep) {
                var $row = this.rows[rStep];
                if (!$row) {
                    return;
                }
                $row.remove();
                var i;
                for(i = rStep + 1; i <= step; i++) {
                    var $iRow = this.rows[i];
                    var toStep = i - 1;
                    $iRow.find("td:first b").text(toStep);
                    this.rows[toStep] = $iRow;
                    $iRow.data('step', toStep);
                }
                delete this.rows[i];
                step--;
                this.firstTurnOffActions();
            },
            moveUp: function (rStep) {
                var toStep = rStep - 1;
                if (toStep < 1) {
                    return;
                }
                var $toRow = this.rows[toStep];
                var $fromRow = this.rows[rStep];
                $toRow.insertAfter($fromRow);

                this.rows[toStep] = $fromRow;
                $fromRow.data('step', toStep);
                $fromRow.find("td:first b").text(toStep);

                this.rows[rStep] = $toRow;
                $toRow.data('step', rStep);
                $toRow.find("td:first b").text(rStep);
                this.firstTurnOffActions();

                if (toStep == 1) {
                    this.turnOnActions($toRow);
                }
            }
        };

        $table.find('a.add').click(function (e) {
            e.preventDefault();
            main.addRow();
        });

        $table.find('tr:not(.actions)').each(function () {
            main.initRow($(this));
        });
    });
</script>
<?php
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/epilog_admin.php");
