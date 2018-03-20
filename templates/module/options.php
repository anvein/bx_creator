<?php if (defined('B_PROLOG_INCLUDED') && (B_PROLOG_INCLUDED === true)) die();

// TODO: переделать это
use Bitrix\Main\Application;
use \BItrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

$moduleName = 'creative.pipedrive';

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

Loc::loadMessages(__FILE__);


$tabControl = new CAdminTabControl(
    'tabControl',
    [
        [
            'DIV' => 'edit1',
            'TAB' => 'Настройки',
            'TITLE' => 'Настройки модуля',
        ]
    ],
    true
);

/**
 * Проверка настроек и сохранение (если нажали Применить/Сохранить)
 */
$oCAdminMessage = new CAdminMessage;
if ((!empty($save) || !empty($apply) || !empty($restore)) && $request->isPost() && check_bitrix_sessid()) {
    if (!empty($restore)) {
        Option::delete($moduleName);
        $oCAdminMessage->ShowMessage([
            'MESSAGE' => Loc::getMessage('REFERENCES_OPTIONS_RESTORED'),
            'TYPE' => 'OK',
        ]);
    } else {
        $error = '';
        $fields = [
            'id_moderator_pipedrive_1',
            'id_moderator_pipedrive_2',
            'id_moderator_pipedrive_3',
            'token_connection_pipedrive_admin',
            'id_value_tag_with_site',
            'id_default_stage_for_buy',
            'id_default_moderator_buy',
        ];

        // валидация
        foreach ($fields as $field) {
            // id модератора 1
            if ($field === 'id_moderator_pipedrive_1' && trim($request->getPost($field)) === '') {
                $error .= Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_ID1_ERROR') . '<br>';
            }

            // токен админа
            if ($field === 'token_connection_pipedrive_admin' && trim($request->getPost($field)) === '') {
                $error .= Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_ADMIT_TOKEN_ERROR') . '<br>';
            }
        }


        if (!$error) {
            // сохранение
            foreach ($fields as $field) {
                if ($request->getPost($field) !== null) {
                    Option::set(
                        $moduleName,
                        $field,
                        $request->getPost($field)
                    );
                }
            }


            $oCAdminMessage->ShowMessage([
                'MESSAGE' => Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_OK_SAVE'),
                'TYPE' => 'OK',
            ]);

        } else {
            $oCAdminMessage->ShowMessage([
                'MESSAGE' => $error,
                'TYPE' => 'ERROR',
            ]);
        }
    }
}
?>

<?php
$tabControl->begin();
$tabControl->beginNextTab();
?>
<form method="POST" action="<?php echo sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID)?>">
    <?php echo bitrix_sessid_post(); ?>
    <?php echo bitrix_sessid_post(); ?>
    <table class="adm-detail-content-table">
        <tr class="heading">
            <td colspan="2">
                <b><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_SECTION_PAGE'); ?></b>
            </td>
        </tr>

        <tr>
            <td class="adm-detail-content-cell-l" width="50%">
                <label for="id_moderator_pipedrive_1"><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_ID_PIPEDRIVE_LABEL_1')?></label>
            </td>
            <td class="adm-detail-content-cell-r" width="50%">
                <input
                    name="id_moderator_pipedrive_1"
                    type="text"
                    id="id_moderator_pipedrive_1"
                    class="adm-detail-content-cell-r"
                    size="12"
                    value="<?=COption::GetOptionString($moduleName, 'id_moderator_pipedrive_1');?>">
            </td>
        </tr>

        <tr>
            <td class="adm-detail-content-cell-l" width="50%">
                <label for="id_moderator_pipedrive_2"><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_ID_PIPEDRIVE_LABEL_2')?></label>
            </td>
            <td class="adm-detail-content-cell-r" width="50%">
                <input
                    name="id_moderator_pipedrive_2"
                    type="text"
                    id="id_moderator_pipedrive_2"
                    class="adm-detail-content-cell-r"
                    size="12"
                    value="<?=COption::GetOptionString($moduleName, 'id_moderator_pipedrive_2');?>">
            </td>
        </tr>

        <tr>
            <td class="adm-detail-content-cell-l" width="50%">
                <label for="id_moderator_pipedrive_3"><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_ID_PIPEDRIVE_LABEL_3')?></label>
            </td>
            <td class="adm-detail-content-cell-r" width="50%">
                <input
                    name="id_moderator_pipedrive_3"
                    type="text"
                    id="id_moderator_pipedrive_3"
                    class="adm-detail-content-cell-r"
                    size="12"
                    value="<?=COption::GetOptionString($moduleName, 'id_moderator_pipedrive_3');?>">
            </td>
        </tr>

        <tr>
            <td class="adm-detail-content-cell-l" width="50%">
                <label for="token_connection_pipedrive_admin"><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_TOKEN_PIPEDRIVE_LABEL_ADMIN')?></label>
            </td>
            <td class="adm-detail-content-cell-r" width="50%">
                <input
                    name="token_connection_pipedrive_admin"
                    type="text"
                    id="token_connection_pipedrive_admin"
                    class="adm-detail-content-cell-r"
                    size="40"
                    value="<?=COption::GetOptionString($moduleName, 'token_connection_pipedrive_admin');?>">
            </td>
        </tr>

        <tr class="heading">
            <td colspan="2">
                <b><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_SECTION_PAGE_BUY'); ?></b>
            </td>
        </tr>

        <tr>
            <td class="adm-detail-content-cell-l" width="50%">
                <label for="id_default_moderator_buy"><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_ID_PIPEDRIVE_BUY_LABEL')?></label>
            </td>
            <td class="adm-detail-content-cell-r" width="50%">
                <input
                    name="id_default_moderator_buy"
                    type="text"
                    id="id_default_moderator_buy"
                    class="adm-detail-content-cell-r"
                    size="12"
                    value="<?=COption::GetOptionString($moduleName, 'id_default_moderator_buy');?>">
            </td>
        </tr>

        <tr>
            <td class="adm-detail-content-cell-l" width="50%">
                <label for="id_default_stage_for_buy"><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_ID_STAGE_DEFAULT_BUY')?></label>
            </td>
            <td class="adm-detail-content-cell-r" width="50%">
                <input
                    name="id_default_stage_for_buy"
                    type="text"
                    id="id_default_stage_for_buy"
                    class="adm-detail-content-cell-r"
                    size="3"
                    value="<?=COption::GetOptionString($moduleName, 'id_default_stage_for_buy');?>">
            </td>
        </tr>


        <tr class="heading">
            <td colspan="2">
                <b><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_SECTION_PAGE_OTHER'); ?></b>
            </td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l" width="50%">
                <label for="id_value_tag_with_site"><?=Loc::getMessage('CREATIVE_PIPEDRIVE_OPTIONS_ID_TAG_IN_SITE')?></label>
            </td>
            <td class="adm-detail-content-cell-r" width="50%">
                <input
                    name="id_value_tag_with_site"
                    type="text"
                    id="id_value_tag_with_site"
                    class="adm-detail-content-cell-r"
                    size="3"
                    value="<?=COption::GetOptionString($moduleName, 'id_value_tag_with_site');?>">
            </td>
        </tr>

        <?php
        $tabControl->endTab();
        $tabControl->buttons([]);
        ?>
    </table>
</form>
<?php $tabControl->end(); ?>
