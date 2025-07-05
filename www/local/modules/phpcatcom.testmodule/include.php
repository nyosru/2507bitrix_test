<?php
// Подключение автозагрузчика классов модуля

use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Подключаем пространство имён вашего модуля
// Обычно в папке /lib/ лежат классы с namespace YourVendor\YourModule

// Регистрируем автозагрузчик для классов модуля
\Bitrix\Main\Loader::registerAutoLoadClasses(
    "phpcatcom.testmodule", // ID модуля
    [
        // Ключ — имя класса с namespace, значение — путь к файлу относительно корня модуля
        "Phpcatcom\\Testmodule\\CurrencyRateTable" => "lib/CurrencyRateTable.php",
//        "Phpcatcom\\TestModule\\SomeClass" => "lib/SomeClass.php",
//        "YourVendor\\YourModule\\AnotherClass" => "lib/AnotherClass.php",
        // Добавляйте по необходимости
    ]
);
