<?php

namespace YourVendor\YourModule;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Класс AnotherClass
 * Пример бизнес-логики модуля
 */
class AnotherClass
{
    /**
     * Метод возвращает приветственное сообщение
     *
     * @param string $name Имя пользователя
     * @return string
     */
    public static function sayHello(string $name): string
    {
        return Loc::getMessage('YOURVENDOR_YOURMODULE_HELLO') . ', ' . htmlspecialchars($name) . '!';
    }

    /**
     * Пример метода, который может выполнять какую-то логику
     */
    public function doSomething()
    {
        // Здесь может быть любая логика вашего модуля
        return "Действие выполнено успешно.";
    }
}
