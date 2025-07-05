<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\PageNavigation;
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

// Идентификаторы фильтра и грида
$filterId = 'phpcatcom_testmodule_filter';
$gridId = 'phpcatcom_testmodule_grid';

// Опции фильтра
$filterOptions = new FilterOptions($filterId);
$filterData = $filterOptions->getFilter([
    ['id' => 'DATE_FROM', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_DATE_FROM'), 'type' => 'date'],
    ['id' => 'DATE_TO', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_DATE_TO'), 'type' => 'date'],
    ['id' => 'COURSE_FROM', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_COURSE_FROM'), 'type' => 'number'],
    ['id' => 'COURSE_TO', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_COURSE_TO'), 'type' => 'number'],
    ['id' => 'CODE', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_CODE'), 'type' => 'string'],
]);

// Формируем фильтр для ORM
$filter = [];

if (!empty($filterData['DATE_FROM'])) {
    $filter['>=DATE'] = Date::createFromUserTime($filterData['DATE_FROM']);
}
if (!empty($filterData['DATE_TO'])) {
    $filter['<=DATE'] = Date::createFromUserTime($filterData['DATE_TO']);
}
if (!empty($filterData['COURSE_FROM'])) {
    $filter['>=COURSE'] = floatval($filterData['COURSE_FROM']);
}
if (!empty($filterData['COURSE_TO'])) {
    $filter['<=COURSE'] = floatval($filterData['COURSE_TO']);
}
if (!empty($filterData['CODE'])) {
    $filter['%CODE'] = $filterData['CODE'];
}

// Опции грида (сортировка, постраничный вывод)
$gridOptions = new GridOptions($gridId);
$sort = $gridOptions->getSorting(['sort' => ['DATE' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$navParams = $gridOptions->getNavParams();

$nav = new PageNavigation($gridId);
$nav->allowAllRecords(false)
    ->setPageSize($navParams['nPageSize'])
    ->initFromUri();

$query = CurrencyRateTable::getList([
    'filter' => $filter,
    'order' => $sort['sort'],
    'limit' => $nav->getPageSize(),
    'offset' => $nav->getOffset(),
]);

$totalCount = CurrencyRateTable::getCount($filter);
$nav->setRecordCount($totalCount);

$rows = [];
while ($item = $query->fetch()) {
    $rows[] = [
        'id' => $item['ID'],
        'data' => $item,
        'columns' => [
            'ID' => $item['ID'],
            'CODE' => htmlspecialcharsbx($item['CODE']),
            'DATE' => $item['DATE'],
            'COURSE' => $item['COURSE'],
        ],
    ];
}

$columns = [
//    ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true],
    ['id' => 'CODE', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_CODE'), 'sort' => 'CODE', 'default' => true],
    ['id' => 'DATE', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_DATE'), 'sort' => 'DATE', 'default' => true],
    ['id' => 'COURSE', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_COURSE'), 'sort' => 'COURSE', 'default' => true],
];

?>

    <form method="GET" id="filter_form" name="filter_form">
        <?php
        $APPLICATION->IncludeComponent(
            "bitrix:main.ui.filter",
            "",
            [
                'FILTER_ID' => $filterId,
                'GRID_ID' => $gridId,
                'FILTER' => [
                    ['id' => 'DATE_FROM', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_DATE_FROM'), 'type' => 'date'],
                    ['id' => 'DATE_TO', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_DATE_TO'), 'type' => 'date'],
                    ['id' => 'COURSE_FROM', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_COURSE_FROM'), 'type' => 'number'],
                    ['id' => 'COURSE_TO', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_COURSE_TO'), 'type' => 'number'],
                    ['id' => 'CODE', 'name' => Loc::getMessage('PHP_CATCOM_TESTMODULE_FIELD_CODE'), 'type' => 'string'],
                ],
                'ENABLE_LABEL' => true,
                'ENABLE_LIVE_SEARCH' => false,
            ],
            false
        );
        ?>
    </form>

<?php
$APPLICATION->IncludeComponent(
    "bitrix:main.ui.grid",
    "",
    [
        'GRID_ID' => $gridId,
        'COLUMNS' => $columns,
        'ROWS' => $rows,
        'NAV_OBJECT' => $nav,
        'TOTAL_ROWS_COUNT' => $totalCount,
        'SHOW_PAGINATION' => true,
        'SHOW_NAVIGATION_PANEL' => true,
        'ALLOW_COLUMNS_SORT' => true,
        'ALLOW_COLUMNS_RESIZE' => true,
        'ALLOW_HORIZONTAL_SCROLL' => true,
        'SHOW_ROW_CHECKBOXES' => false,
        'SHOW_ROW_ACTIONS_MENU' => false,
        'SHOW_GRID_SETTINGS_MENU' => true,
    ]
);
?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
