<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE == 'BE') {
	// Register hook to add the cache clear button to configured items in different views
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/template.php']['docHeaderButtonsHook']['Vcc'] =
		'CPSIT\\Vcc\\Hook\\DocHeaderButtonsHook->addButton';

	// Initialize array for internal hooks
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['vcc']['hooks']['communicationService'])) {
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['vcc']['hooks']['communicationService'] = array();
	}
}
