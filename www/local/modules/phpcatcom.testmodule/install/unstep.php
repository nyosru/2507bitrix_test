<?php
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= Loc::getMessage("PHPCATCOM_TESTMODULE_UNINSTALL_TITLE") ?></title>
</head>
<body>
<h2><?= Loc::getMessage("PHPCATCOM_TESTMODULE_MODULE_NAME") ?></h2>
<p><?= Loc::getMessage("PHPCATCOM_TESTMODULE_UNINSTALL_SUCCESS") ?></p>

<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
    <input type="submit" name="finish" value="<?= Loc::getMessage("PHPCATCOM_TESTMODULE_UNINSTALL_FINISH") ?>">
