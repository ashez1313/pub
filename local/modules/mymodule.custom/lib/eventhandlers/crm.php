<?php

namespace MyModule\Custom\EventHandlers;

use Bitrix\Main\UserTable;
use Bitrix\Crm\DealTable;

/**
 * Класс для обработчиков событий модуля CRM
 */
class crm
{
    /**
     * Обработчик запрета изменения ответственного в сделке
     * @param array $arFields
     * @return void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function onBeforeCrmDealUpdateHandler(array &$arFields) : void
    {
        // проверка заполненности полей "Ответственный" и "Кем изменен"
        if (
            isset ($arFields["ASSIGNED_BY_ID"]) && intval($arFields["ASSIGNED_BY_ID"]) &&
            isset ($arFields["MODIFY_BY_ID"]) && intval($arFields["MODIFY_BY_ID"]) > 0
        ) {
            // список групп пользователя, кто вносит изменения
            $userGroups = UserTable::getUserGroupIds(intval($arFields["MODIFY_BY_ID"]));

            // если пользователь не входит в разрешенную группу
            if (!in_array(ASSIGNED_CHANGE_GROUP_ID, $userGroups)) {
                // текущая сделка
                $currentDeal = DealTable::getByPrimary($arFields["ID"])->fetchObject();

                // проверяем, был ли изменен ответственный
                if ($currentDeal->getAssignedById() != $arFields["ASSIGNED_BY_ID"]) {
                    // если да, то подменяем на исходного
                    $arFields['ASSIGNED_BY_ID'] = $currentDeal->getAssignedById();

                    // уведомление пользователю об ошибке
                    if (\Bitrix\Main\Loader::IncludeModule('im')) {
                        $arFieldsIm = array(
                            "NOTIFY_TITLE" => "Недостаточно прав для изменения ответственного!", //заголовок
                            "MESSAGE" => 'Недостаточно прав для изменения ответственного!',
                            "MESSAGE_TYPE" => IM_MESSAGE_SYSTEM, // системное сообщение
                            "TO_USER_ID" => $arFields["MODIFY_BY_ID"],
                            "FROM_USER_ID" => $arFields["MODIFY_BY_ID"],
                            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
                            "NOTIFY_MODULE" => "main",
                            "NOTIFY_EVENT" => "manage",
                        );
                        \CIMMessenger::Add($arFieldsIm);
                    }
                }
            }
        }
    }
}