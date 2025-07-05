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

$APPLICATION->SetTitle(Loc::getMessage("PHP_CATCOM_TESTMODULE_LIST_TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// Обработка сохранения (добавление/редактирование)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_bitrix_sessid()) {
    $id = intval($_POST['ID'] ?? 0);
    $code = strtoupper(trim($_POST['CODE'] ?? ''));
    $date = trim($_POST['DATE'] ?? '');
    $course = floatval($_POST['COURSE'] ?? 0);

    $errors = [];

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
        if ($id > 0) {
            $result = CurrencyRateTable::update($id, [
                'CODE' => $code,
                'DATE' => $dateObj,
                'COURSE' => $course,
            ]);
        } else {
            $result = CurrencyRateTable::add([
                'CODE' => $code,
                'DATE' => $dateObj,
                'COURSE' => $course,
            ]);
        }

        if ($result->isSuccess()) {
            LocalRedirect($APPLICATION->GetCurPageParam("", ["ID", "edit"]));
        } else {
            $errors = $result->getErrorMessages();
        }
    }
}

// Если редактируем запись — загружаем данные
$editId = intval($_GET['edit'] ?? 0);
$editRecord = null;
if ($editId > 0) {
    $editRecord = CurrencyRateTable::getById($editId)->fetch();
}

?>

<?php if (!empty($errors)): ?>
    <div class="adm-info-message adm-info-message-error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialcharsbx($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($editRecord): ?>
    <h2><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_EDIT_TITLE") ?></h2>
    <form method="post" action="">
        <?= bitrix_sessid_post() ?>
        <input type="hidden" name="ID" value="<?= $editRecord['ID'] ?>">
        <table class="adm-detail-content-table edit-table">
            <tr>
                <td><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_CODE") ?>:</td>
                <td><input type="text" name="CODE" maxlength="3" value="<?= htmlspecialcharsbx($editRecord['CODE']) ?>" required></td>
            </tr>
<!--            <tr><td>--><?// echo '<pre>',print_r($editRecord),'</pre>'; ?><!--</td></tr>-->
            <tr>
                <td><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_DATE") ?>:</td>
                <td>
                    <?php
                    $dateValue = $editRecord['DATE'] instanceof \Bitrix\Main\Type\Date
                    ? $editRecord['DATE']->format('Y-m-d')
                    : substr($editRecord['DATE'], 0, 10);
                    ?>
                    <input type="date" name="DATE" value="<?= $dateValue ?>" required></td>
            </tr>
            <tr>
                <td><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_COURSE") ?>:</td>
                <td><input type="number" step="0.0001" name="COURSE" value="<?= htmlspecialcharsbx($editRecord['COURSE']) ?>" required></td>
            </tr>
        </table>
        <input type="submit" value="<?= Loc::getMessage("PHP_CATCOM_TESTMODULE_SAVE_BUTTON") ?>">
        <a href="<?= $APPLICATION->GetCurPage() ?>"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_CANCEL") ?></a>
    </form>
<?php else: ?>
    <h2><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_LIST_TITLE") ?></h2>
    <a href="/bitrix/admin/phpcatcom_testmodule_add.php?lang=ru"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_ADD_NEW") ?></a>
<style>
    .adm-list-table th { text-align: left; }
    .adm-list-table tbody { background-color: #ffffff; }
.adm-list-table tbody tr:nth-child(even) {  background-color: #eeeeee;}

    .adm-list-table td{ padding: 2px; }
</style>
    <table class="adm-list-table" style="width: 600px; margin-top: 10px;">
        <thead>
        <tr>
            <th>ID</th>
            <th><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_CODE") ?></th>
            <th><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_DATE") ?></th>
            <th><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_FIELD_COURSE") ?></th>
            <th><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_ACTIONS") ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $rs = CurrencyRateTable::getList(['order' => ['DATE' => 'DESC']]);
        while ($item = $rs->fetch()):
            ?>
            <tr>
                <td><?= $item['ID'] ?></td>
                <td><?= htmlspecialcharsbx($item['CODE']) ?></td>
                <td><?= htmlspecialcharsbx($item['DATE']) ?></td>
                <td><?= htmlspecialcharsbx($item['COURSE']) ?></td>
                <td>
                    <a href="?edit=<?= $item['ID'] ?>"><?= Loc::getMessage("PHP_CATCOM_TESTMODULE_EDIT") ?></a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
