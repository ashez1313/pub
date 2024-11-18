<?php

namespace MyModule\Custom\EventHandlers;

use Bitrix\Main\Config\Option;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Engine\CurrentUser;

/**
 * Класс обработчиков события модуля Main
 */
class Main
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
            /// список групп пользователя, кто вносит изменения
            $userGroups = UserTable::getUserGroupIds(CurrentUser::get()->getId());

            // массив разрешенных групп из настроек модуля
            $allowedGroups = explode(',', Option::get('mymodule.custom', "mymodule_groups_assigned_change"));

            // если пользователь не входит в разрешенные группы
            if (!array_intersect($allowedGroups, $userGroups)) {
                // то загружаем расширение блокировки ответственного
                \Bitrix\Main\UI\Extension::load('mymodule.custom.denyAssignedChange');
            }
        }
    }
}