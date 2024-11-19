<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Application;

// языковые сообщения
Loc::loadMessages(__FILE__);

/**
 * Класс модуля
 */
class mymodule_custom extends CModule
{
    /**
     * ID модуля
     * @return string
     */
    public static function getModuleId()
    {
        return basename(dirname(__DIR__));
    }

    /**
     * Конструктор
     */
    public function __construct()
    {
        $arModuleVersion = [];
        include(dirname(__FILE__) . "/version.php");
        $this->MODULE_ID = self::getModuleId();
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("MY_MODULE_CUSTOM_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("MY_MODULE_CUSTOM_DESC");

        $this->PARTNER_NAME = Loc::getMessage("MY_MODULE_CUSTOM_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("MY_MODULE_CUSTOM_PARTNER_URI");
    }

    /**
     * Метод для установки модуля
     * @return bool
     */
    public function doInstall()
    {
        try {
            if (!$this->isD7Supported()) {
                throw new SystemException('Версия модуля main <14.0.0 и не поддерживает D7');
            }

            // регистрируем модуль
            Main\ModuleManager::registerModule($this->MODULE_ID);
            // копируем файлы модуля
            $this->InstallFiles();

        } catch (SystemException $e) {
            global $APPLICATION;
            $APPLICATION->ThrowException($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Метод для удаления модуля
     * @return bool
     */
    public function doUninstall()
    {
        try {
            // отменяем регистрацию модуля
            Main\ModuleManager::unRegisterModule($this->MODULE_ID);
            // удаляем файлы модуля
            $this->UnInstallFiles();
        } catch (Exception $e) {
            global $APPLICATION;
            $APPLICATION->ThrowException($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Метод копирования файлов, необходимых для работы модуля, в необходимые директории
     * @return bool
     */
    public function InstallFiles(): bool
    {
        // копируем расширения из папки js в /local/js
        CopyDirFiles(
            __DIR__ . "/js",
            Application::getDocumentRoot() . "/local/js",
            true, // с перезаписью
            true  // рекурсивно
        );

        return true;
    }

    /**
     * Метод для удаления файлов модуля
     * @return bool
     */
    public function UnInstallFiles(): bool
    {
        $moduleDirName = str_replace('.', '/', $this->MODULE_ID);
        // удаляем js-расширения модуля
        if (is_dir(Application::getDocumentRoot() . "/local/js/" . $moduleDirName)) {
            DeleteDirFilesEx(
                "/local/js/" . $moduleDirName
            );
        }

        return true;
    }

    /**
     * Проверка, поддерживается ли D7
     * @return bool
     */
    public function isD7Supported(): bool
    {
        return version_compare(
            \Bitrix\Main\ModuleManager::getVersion("main"),
            "14.00.00"
        );
    }
}