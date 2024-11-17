<?php

namespace MyModule\Custom\EventHandlers;

use \Bitrix\Main\UserTable;
use \Bitrix\Main\Engine\CurrentUser;

/**
 * Класс обработчиков события модуля Main
 */
class main
{
    /**
     * Загрузка расширения блокировки ответственного в интерфейсе карточки сделки
     * @return void
     * @throws \Bitrix\Main\LoaderException
     */
    public static function denyAssignedChangeExtension(): void
    {
        // параметры запроса
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        // проверка на ajax
        if ($request->isAjaxRequest()) {
            return;
        }

        if (preg_match('@/crm/deal/details/[0-9]+/@i', $request->getRequestedPage())) {
            $userGroups = UserTable::getUserGroupIds(CurrentUser::get()->GetID());

            if (in_array(ASSIGNED_CHANGE_GROUP_ID, $userGroups)) {
                \Bitrix\Main\UI\Extension::load('mymodule.custom.denyAssignedChange');
            }
        }
    }

}