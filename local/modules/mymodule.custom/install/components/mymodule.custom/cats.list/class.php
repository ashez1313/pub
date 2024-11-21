<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Errorable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Web;
use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

/**
 * Основной класс компонента списка котов
 */
class CatsList extends CBitrixComponent implements Errorable
{
    protected ErrorCollection $errorCollection;

    /**
     * Главный метод вывода компонента
     * @return void
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function executeComponent()
    {
        // проверка необходимых для работы модулей
        $this->checkModules();

        // проверка, что указан id HL-блока
        if (empty($this->arParams['HL_BLOCK'])) {
            throw new SystemException(Loc::getMessage('HLBL_NOT_SET'));
        }

        // получаем hl-блок
        $hlbl = (int)$this->arParams['HL_BLOCK'];
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        // получаем данные из hl-блока
        $rsData = $entity_data_class::getList(array(
            'order' => array(
                'ID' => 'ASC'
            ),
            'cache' => array(
                'ttl' => (int)$this->arParams['CACHE_TIME']
            ),
        ))->fetchAll();

        // формируем $arResult
        foreach ($rsData as $item) {
            if ($item[$this->arParams['HL_BLOCK_FIELDS_PICTURE']]) {
                $item[$this->arParams['HL_BLOCK_FIELDS_PICTURE']] = CFile::GetFileArray(
                    $item[$this->arParams['HL_BLOCK_FIELDS_PICTURE']]
                );
            }
            $arr['NAME'] = $item[$this->arParams['HL_BLOCK_FIELDS_NAME']];
            $arr['DESCRIPTION'] = $item[$this->arParams['HL_BLOCK_FIELDS_DESC']];
            $arr['PICTURE'] = $item[$this->arParams['HL_BLOCK_FIELDS_PICTURE']];
            $this->arResult[] = $arr;
        }

        $this->includeComponentTemplate();
    }

    /**
     * Проверка, что подключены нужные модули
     * @return true
     * @throws SystemException
     * @throws \Bitrix\Main\LoaderException
     */
    public function checkModules()
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new SystemException(Loc::getMessage('HLBL_MODULE_NOT_INSTALLED'));
        }

        return true;
    }

    /**
     * Получить массив ошибок
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errorCollection->toArray();
    }

    /**
     * Сообщение об ошибке по коду ошибки
     * @param $code
     * @return Error
     */
    public function getErrorByCode($code)
    {
        return $this->errorCollection->getErrorByCode($code);
    }
}