<?php
/**
 * Загрузчик классов
 * Файл необязателен, т.к. при правильных именах классов поддерживается автозагрузка.
 * А при неверных именах будет ошибка при установке.
 * Но автозагрузка работает медленнее
 */

Bitrix\Main\Loader::registerAutoloadClasses(
    "mymodule.custom",
    [
        // класс для обработчиков событий модуля main
        'MyModule\\Custom\\EventHandlers\\Main' => 'lib/eventhandlers/main.php',
        // класс для обработчиков событий модуля crm
        'MyModule\\Custom\\EventHandlers\\Crm' => 'lib/eventhandlers/crm.php',
    ]
);