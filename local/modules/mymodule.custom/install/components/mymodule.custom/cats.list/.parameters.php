<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

if (!Loader::includeModule('highloadblock')) {
    throw new \Bitrix\Main\SystemException('');
}


// Cписок HL-блоков
$arHlBlocksList = [];

$hlblockIterator = HL\HighloadBlockTable::getList();
while ($hlblock = $hlblockIterator->fetch()) {
    $arHlBlocksList[$hlblock['ID']] = '[' . $hlblock['ID'] . '] ' . $hlblock['NAME'];;
}

// Поля выбранного HL-блока
if (!empty($arCurrentValues['HL_BLOCK'])) {
    $hlblockId = $arCurrentValues['HL_BLOCK'];
    $hlblock = HL\HighloadBlockTable::getById($hlblockId)->fetch();
    $hlEntity = HL\HighloadBlockTable::compileEntity($hlblock);
    $hlFields = $hlEntity->getFields();
    foreach ($hlFields as $fieldName => $field) {
        $arHlBlocksFields[$fieldName] = $fieldName;
    }
}

// параметры компонента
$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        'HL_BLOCK' => [
            'PARENT' => 'BASE',
            'NAME' => GetMessage('HL_BLOCK_LIST'),
            'TYPE' => 'LIST',
            'VALUES' => $arHlBlocksList,
            'REFRESH' => 'Y',
        ],
        'HL_BLOCK_FIELDS_NAME' => [
            'PARENT' => 'BASE',
            'NAME' => 'Поле с названием элемента',
            'TYPE' => 'LIST',
            'VALUES' => $arHlBlocksFields,
            'REFRESH' => 'N',
        ],
        'HL_BLOCK_FIELDS_DESC' => [
            'PARENT' => 'BASE',
            'NAME' => 'Поле с описанием элемента',
            'TYPE' => 'LIST',
            'VALUES' => $arHlBlocksFields,
            'REFRESH' => 'N',
        ],
        'HL_BLOCK_FIELDS_PICTURE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Поле с картинкой элемента',
            'TYPE' => 'LIST',
            'VALUES' => $arHlBlocksFields,
            'REFRESH' => 'N',
        ],
        'CACHE_TIME' => [
            'DEFAULT' => '3600'
        ],
    ],
];
