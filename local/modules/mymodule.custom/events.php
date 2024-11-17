<?php

use \Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

/*********************************************
 ***** Обработчики кастомных событий *****
 *********************************************/

// запрет смены ответственного в интерфейсе
$eventManager->addEventHandlerCompatible(
    'main',
    'OnEpilog',
    [
        "\\MyModule\\Custom\\EventHandlers\\Main",
        "denyAssignedChangeExtension"
    ]
);

// запрет смены ответственного на бэкенде
$eventManager->addEventHandlerCompatible(
    'crm',
    'OnBeforeCrmDealUpdate',
    [
        "\\MyModule\\Custom\\EventHandlers\\Crm",
        "onBeforeCrmDealUpdateHandler"
    ]
);