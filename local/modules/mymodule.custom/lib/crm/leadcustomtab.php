<?php

namespace MyModule\Custom\Crm;

/**
 * Класс для создания собственной вкладки в лидах
 */
class LeadCustomTab
{
    // права пользователя
    protected \CCrmPerms $userPermissions;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->userPermissions = \CCrmPerms::GetCurrentUserPermissions();
    }

    /**
     * Метод добавляет пользовательскую вкладку в лиды и возвращает модифицированный массив вкладок
     * @param int $elementId
     * @param int $entityTypeId
     * @param array $currentTabs
     * @return array
     */
    public function getModifiedTabs(int $elementId, int $entityTypeId, array $currentTabs = [])
    {
        // если текущая сущность - лид, и у пользователя есть права на просмотр этого лида
        if ($entityTypeId === \CCrmOwnerType::Lead
            && \CCrmLead::CheckReadPermission($elementId, $this->userPermissions)) {
            // то добавляем нашу кастомную вкладку с highload-блоком
            $currentTabs[] = [
                'id' => 'component_cats',
                'name' => 'Кошки',
                'enabled' => !empty($elementId),
                'loader' => [
                    'serviceUrl' => '/local/public/hlcats/list.php' // страница с компонентом
                ]
            ];
        }

        return $currentTabs;
    }
}