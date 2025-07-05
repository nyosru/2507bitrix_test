<?php
namespace Phpcatcom\Testmodule;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class CurrencyRateTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'phpcatcom_testmodule';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => 'ID',
            ]),
            new Entity\StringField('CODE', [
                'required' => true,
                'validation' => function() {
                    return [
                        new Entity\Validator\Length(null, 3),
                    ];
                },
                'title' => 'Код валюты',
            ]),
            new Entity\DateField('DATE', [
                'required' => true,
                'title' => 'Дата',
            ]),
            new Entity\FloatField('COURSE', [
                'required' => true,
                'title' => 'Курс',
            ]),
        ];
    }
}
