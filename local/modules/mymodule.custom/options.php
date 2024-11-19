<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

// подключение языковых файлов
Loc::loadMessages(__FILE__);
// получение запроса
$request = HttpApplication::getInstance()->getContext()->getRequest();
// id модуля
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
// права текущего пользователя на модуль
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
// если нет прав - ошибка "Доступ запрещен"
if ($POST_RIGHT < "S") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

// подключение модуля
Loader::includeModule($module_id);

// получаем список всех активных групп
$resGroups = \Bitrix\Main\GroupTable::getList([
    'order' => ['ID'], // сортируем по ID группы
    'select' => ['ID', 'NAME'],
    'filter' => ['ACTIVE' => 'Y',] // все активные группы
])->fetchCollection();
// массив с группами
$arGroups = [];
foreach ($resGroups as $resGroup) {
    $arGroups[$resGroup['ID']] = $resGroup['NAME'];
}

// настройки модуля для админки в том числе значения по умолчанию
$aTabs = [
    [
        // значение будет вставленно во все элементы вкладки для идентификации (используется для javascript)
        "DIV" => "edit-1",
        // название вкладки в табах
        "TAB" => "Общие настройки",
        // заголовок и всплывающее сообщение вкладки
        "TITLE" => "Общие настройки модуля",
        // массив с опциями секции
        "OPTIONS" => [
            "Изменение ответственного в сделках",
            [
                // имя элемента формы, для хранения в бд
                "mymodule_groups_assigned_change",
                // поясняющий текст
                "Группы, кто может менять ответственных",
                // значение по умолчанию - группа админов (constants.php)
                strval(ASSIGNED_CHANGE_GROUP_ID),
                // тип элемента формы "multi select"
                ["multiselectbox", $arGroups]
            ]
        ]
    ],
    [
        // значение будет вставленно во все элементы вкладки для идентификации (используется для javascript)
        "DIV" => "edit2",
        // название вкладки в табах из основного языкового файла битрикс
        "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
        // заголовок и всплывающее сообщение вкладки из основного языкового файла битрикс
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")
    ]
];

// проверяем текущий POST запрос и сохраняем выбранные пользователем настройки
if ($request->isPost() && check_bitrix_sessid()) {
    // цикл по вкладкам
    foreach ($aTabs as $aTab) {
        // цикл по заполненым пользователем данным
        foreach ($aTab["OPTIONS"] as $arOption) {
            // если это название секции, переходим к следующий итерации цикла
            if (!is_array($arOption)) {
                continue;
            }
            // проверяем POST запрос, если инициатором выступила кнопка с name="Submit", то сохраняем настройки в базу данных
            if ($request["Submit"]) {
                // получаем в переменную $optionValue введенные пользователем данные
                $optionValue = $request->getPost($arOption[0]);
                // устанавливаем выбранные значения параметров и сохраняем в базу данных
                // хранить можем только текст, поэтому в случае массива сохраняем значения в строку через запятую
                Option::set($module_id, $arOption[0],
                    is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            }
            // проверяем POST запрос, если инициатором выступила кнопка с name="default" сохраняем дефолтные настройки в базу данных
            if ($request["default"]) {
                // устанавливаем значения параметров по умолчанию и сохраняем в базу данных
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }
}
// отрисовываем форму, для этого создаем новый экземпляр класса CAdminTabControl, куда и передаём массив с настройками
$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);
// отображаем заголовки закладок
$tabControl->Begin();
?>
    <form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>"
          method="post">
        <? foreach ($aTabs as $aTab) {
            if ($aTab["OPTIONS"]) {
                // завершает предыдущую закладку, если она есть, начинает следующую
                $tabControl->BeginNextTab();
                // отрисовываем форму из массива
                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            }
        }
        // завершает предыдущую закладку, если она есть, начинает следующую
        $tabControl->BeginNextTab();
        // выводим форму управления правами в настройках текущего модуля
        require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php";
        // подключаем кнопки отправки формы
        $tabControl->Buttons();
        // выводим скрытый input с идентификатором сессии
        echo(bitrix_sessid_post());
        // выводим стандартные кнопки отправки формы
        ?>
        <input class="adm-btn-save" type="submit" name="Submit" value="Применить"/>
        <input type="submit" name="default" value="По умолчанию"/>
    </form>
<?php
// обозначаем конец отрисовки формы
$tabControl->End();