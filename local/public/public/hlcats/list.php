<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

$GLOBALS['APPLICATION']->IncludeComponent(
    "mymodule.custom:cats.list",
    "",
    [
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "HL_BLOCK" => "2",
        "HL_BLOCK_FIELDS_DESC" => "UF_HL_ELEMENT_DESCRIPTION",
        "HL_BLOCK_FIELDS_NAME" => "UF_HL_ELEMENT_TITLE",
        "HL_BLOCK_FIELDS_PICTURE" => "UF_HL_ELEMENT_PICTURE",
    ]
);

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php';
?>
