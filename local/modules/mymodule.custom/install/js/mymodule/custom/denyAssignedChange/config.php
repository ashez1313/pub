<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => './css/style.css',
    'js' => './dist/script.bundle.js',
    'rel' => [
		'main.polyfill.core',
	],
    'skip_core' => true,
];
