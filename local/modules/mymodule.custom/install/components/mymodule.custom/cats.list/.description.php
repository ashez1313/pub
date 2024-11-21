<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("CATS_COMPONENT_NAME"),
	"DESCRIPTION" => GetMessage("CATS_COMPONENT_DESCRIPTION"),
	"ICON" => "/images/icon.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "owner_components",
			"NAME" => GetMessage("CATS_COMPONENT_SECTION"),
			"SORT" => 10,
		)
	),
);

?>