<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Phpcatcom\Testmodule\CurrencyRateTable;
use Bitrix\Main\Type\Date;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

Loc::loadMessages(__FILE__);

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

Loader::includeModule('phpcatcom.testmodule');

$APPLICATION->SetTitle(Loc::getMessage("PHP_CATCOM_TESTMODULE_ADD_EDIT_TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$errors = [];
$success = false;

$id = isset($_REQUEST['ID']) ? (int)$_REQUEST['ID'] : 0;
$isEdit = ($id > 0);
$fields = [
    'CODE' => '',
    'DATE' => '',
    'COURSE' => '',
];

// Если редактируем — загружаем данные
if ($isEdit) {
    $record = CurrencyRateTable::getById($id)->fetch();
    if (!$record) {
        echo '<div class="adm-info-message adm-info-message-error">'.Loc::getMessage("PHP_CATCOM_TESTMODULE_RECORD_NOT_FOUND").'</div>';
        require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
        exit;
    }
    // Преобразуем дату в строку формата YYYY-MM-DD
    if ($record['DATE'] instanceof \Bitrix\Main\Type\Date || $record['DATE'] instanceof \Bitrix\Main\Type\DateTime) {
        $dateString = $record['DATE']->format('Y-m-d');
    } elseif (is_string($record['DATE'])) {
        $dateString = substr($record['DATE'], 0, 10);
    } else {
        $dateString = '';
    }

    $fields = [
        'CODE' => $record['CODE'],
        'DATE' => $dateString,
        'COURSE' => $record['COURSE'],
    ];
}

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_bitrix_sessid()) {
    $fields['CODE'] = strtoupper(trim($_POST['CODE'] ?? ''));
    $fields['DATE'] = trim($_POST['DATE'] ?? '');
    $fields['COURSE'] = trim($_POST['COURSE'] ?? '');

    // Валидация
    if (strlen($fields['CODE']) !== 3) {
        $errors[] = Loc::getMessage("PHP_CATCOM_TESTMODULE_ERROR_CODE");
    }
    if (!$fields['DATE'] || !($dateObj = Date::createFromPhp(new \DateTime($fields['DATE'])))) {
        $errors[] = Loc::getMessage("PHP_CATCOM_TESTMODULE_ERROR_DATE");
    }
    if (!is_numeric($fields['COURSE']) || floatval($fields['COURSE']) <= 0) {
        $errors[] = Loc::getMessage("PHP_CATCOM_TESTMODULE_ERROR_COURSE");
    }

    if (empty($errors)) {
        $data = [
            'CODE' => $fields['CODE'],
            'DATE' => $dateObj,
            'COURSE' => floatval($fields['COURSE']),
        ];

        if ($isEdit) {
            $result = CurrencyRateTable::update($id, $data);
        } else {
            $result = CurrencyRateTable::add($data);
        }

        if ($result->isSuccess()) {
            LocalRedirect('/bitrix/admin/phpcatcom_testmodule_list.php?lang='.LANGUAGE_ID);
        } else {
            $errors = array_merge($errors, $result->getErrorMessages());
        }
    }
}

// Вывод ошибок
if (!empty($errors)) {
    echo '<div class="adm-info-message adm-info-message-error"><ul>';
    foreach ($errors as $error) {
        echo '<li>'.htmlspecialcharsbx($error).'</li>';
    }
    echo '</ul></div>';
}
?>

    <form method="post" action="">
        <?= bitrix_sessid_post() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="ID" value="<?= $id ?>">
        <?php endif; ?>
        <table class="adm-detail-content-table edit-table">
            <tr>
                <td width="40%"><label for="code"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_CODE") ?>:</label></td>
                <td width="60%"><input type="text" name="CODE" id="code" maxlength="3" value="<?= htmlspecialcharsbx($fields['CODE']) ?>" required></td>
            </tr>
            <tr>
                <td><label for="date"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_DATE") ?>:</label></td>
                <td><input type="date" name="DATE" id="date" value="<?= htmlspecialcharsbx($fields['DATE']) ?>" required></td>
            </tr>
            <tr>
                <td><label for="course"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_COURSE") ?>:</label></td>
                <td><input type="number" step="0.0001" name="COURSE" id="course" value="<?= htmlspecialcharsbx($fields['COURSE']) ?>" required></td>
            </tr>
        </table>
        <button
                type="submit" ><?= $isEdit ? Loc::getMessage("PHP_CATCOM_TESTMODULE_SAVE_BUTTON") : Loc::getMessage("PHP_CATCOM_TESTMODULE_ADD_BUTTON") ?></button>
        <a href="/bitrix/admin/phpcatcom_testmodule_list.php?lang=<?= LANGUAGE_ID ?>"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_CANCEL") ?></a>
    </form>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
