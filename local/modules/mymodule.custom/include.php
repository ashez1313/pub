<?php

/**
 * Подключение файлов
 */

foreach ([
             __DIR__ . '/constants.php', // константы
             __DIR__ . '/loadClasses.php', // загрузка классов
             __DIR__ . '/events.php', // подписки на события
         ]
         as $filePath) {
    if (file_exists($filePath)) {
        require_once($filePath);
    }
}