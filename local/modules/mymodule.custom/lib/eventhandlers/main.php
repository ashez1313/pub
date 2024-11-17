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

        // если пользователь находится на странице карточки сделки
        if (preg_match('@/crm/deal/details/[0-9]+/@i', $request->getRequestedPage())) {
            // массив групп текущего пользователя
            $userGroups = UserTable::getUserGroupIds(CurrentUser::get()->GetID());

            // если пользователь не входит в разрешенную группу
            if (!in_array(ASSIGNED_CHANGE_GROUP_ID, $userGroups)) {
                // то загружаем расширение блокировки ответственного
                \Bitrix\Main\UI\Extension::load('mymodule.custom.denyAssignedChange');
            }
        }
    }

}