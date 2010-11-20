<?php

if ( php_sapi_name() != 'cli' ) {
	exit;
}

$buildDate = date('Y-m-d H:i:s');
echo <<<JS
/*
 * THIS FILE IS GENERATED BY src/build.php
 * ALTERATIONS IN THIS FILE WILL BE OVERWRITTEN.
 *
 * Built on $buildDate.
 */

/**
 * NextShout namespace.
 */
var NextShout = {};
NextShout.options = {};

/** @param {jQuery} $ jQuery Object */
!function ($, window, document, _undefined)
{
JS;

$files = array('base', 'shout', 'list', 'box', 'queue', 'setup');
foreach ( $files as $file ) {
	echo "\n// ===== $file.js =====\n";
	echo file_get_contents(__DIR__ . "/$file.js");
	echo "// ===== / $file.js =====\n";
}

echo <<<JS
}
(jQuery, this, document);
JS;
