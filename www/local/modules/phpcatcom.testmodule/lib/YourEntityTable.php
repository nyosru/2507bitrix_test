<?php
namespace YourVendor\YourModule;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class YourEntityTable extends Entity\DataManager
{
    // Имя таблицы в базе данных
    public static function getTableName()
    {
        return 'yourvendor_yourtable';
    }

    // Описание структуры таблицы (полей)
    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => 'ID',
            ]),
            new Entity\StringField('TITLE', [
                'required' => true,
                'validation' => function() {
                    return [
                        new Entity\Validator\Length(null, 255),
                    ];
                },
                'title' => 'Заголовок',
            ]),
            new Entity\IntegerField('SORT', [
                'default_value' => 500,
                'title' => 'Сортировка',
            ]),
            new Entity\DatetimeField('CREATED', [
                'default_value' => function() {
                    return new Type\DateTime();
                },
                'title' => 'Дата создания',
            ]),
        ];
    }
}
