<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Phpcatcom\Testmodule\CurrencyRateTable;
use Bitrix\Main\Type\Date;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

Loc::loadMessages(__FILE__);

// Проверяем права доступа
if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

Loader::includeModule('phpcatcom.testmodule');

$errors = [];
$success = false;

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_bitrix_sessid()) {
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $date = trim($_POST['date'] ?? '');
    $course = floatval($_POST['course'] ?? 0);

    if (strlen($code) !== 3) {
        $errors[] = Loc::getMessage("PHP_CATCOM_TESTMODULE_ERROR_CODE");
    }
    if (!$date || !($dateObj = Date::createFromPhp(new \DateTime($date)))) {
        $errors[] = Loc::getMessage("PHP_CATCOM_TESTMODULE_ERROR_DATE");
    }
    if ($course <= 0) {
        $errors[] = Loc::getMessage("PHP_CATCOM_TESTMODULE_ERROR_COURSE");
    }

    if (empty($errors)) {
        $result = CurrencyRateTable::add([
            'CODE' => $code,
            'DATE' => $dateObj,
            'COURSE' => $course,
        ]);

        if ($result->isSuccess()) {
            $success = true;
        } else {
            $errors = $result->getErrorMessages();
        }
    }
}

$APPLICATION->SetTitle(Loc::getMessage("PHP_CATCOM_TESTMODULE_ADD_TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?php if ($success): ?>
    <div class="adm-info-message">
        <?= Loc::getMessage("PHP_CATCOM_TESTMODULE_ADD_SUCCESS") ?>
    </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="adm-info-message adm-info-message-error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialcharsbx($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

    <form method="post" action="">
        <?= bitrix_sessid_post() ?>
        <table class="adm-detail-content-table edit-table">
            <tr>
                <td width="40%"><label for="code"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_CODE") ?>:</label></td>
                <td width="60%"><input type="text" name="code" id="code" maxlength="3" value="<?= htmlspecialcharsbx($_POST['code'] ?? '') ?>" required></td>
            </tr>
            <tr>
                <td><label for="date"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_DATE") ?>:</label></td>
                <td><input type="date" name="date" id="date" value="<?= htmlspecialcharsbx($_POST['date'] ?? '') ?>" required></td>
            </tr>

            <tr>
                <td><label for="course"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_COURSE") ?>:</label></td>
                <td><input type="number" step="0.0001" name="course" id="course" value="<?= htmlspecialcharsbx($_POST['course'] ?? '') ?>" required></td>
            </tr>
        </table>
        <input type="submit" value="<?= Loc::getMessage("PHP_CATCOM_TESTMODULE_ADD_BUTTON") ?>">
    </form>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
