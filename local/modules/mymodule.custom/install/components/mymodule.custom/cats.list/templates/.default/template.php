<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

/** @var array $arResult */
?>

<!-- Свежий Bootstrap на момент кодирования -->
<script type="text/css" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></script>

<!-- Навигация -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?= Loc::getMessage('CATS_TABLE_TITLE')?></a>
    </div>
</nav>

<div id="hlbl-content" class="container-fluid">
    <!-- Таблица с выводом элементов hl-блока -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?= Loc::getMessage('CATS_TABLE_HEADER_NAME')?></th>
            <th><?= Loc::getMessage('CATS_TABLE_HEADER_PICTURE')?></th>
            <th><?= Loc::getMessage('CATS_TABLE_HEADER_DESCRIPTION')?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($arResult as $arItem) :
            ?>
            <tr>
                <!-- Название кота -->
                <td><?= $arItem["NAME"] ?></td>
                <td>
                    <!-- Фото кота -->
                    <img class="custom-hl-img" src="<?= $arItem['PICTURE']['SRC'] ?>" alt="<?= $arItem['NAME'] ?>"
                         width="400px" style="border-radius: 5px;"/>
                </td>
                <!-- Описание кота -->
                <td><?= $arItem["DESCRIPTION"] ?></td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
</div>


