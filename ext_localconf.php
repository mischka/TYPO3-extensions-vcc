<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


// HTTP Header extension
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['isOutputting']['vcc'] = 'CPSIT\\Vcc\\Hook\\HttpHook->main';
