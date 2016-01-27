<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$vccPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('vcc');
$vccRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('vcc');

// Register sprite icons
$icons = array(
	'clearVarnishCache' => $vccRelPath . 'Resources/Public/Icons/CachePlugin.png',
);
\TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons($icons, 'vcc');

// Add default module settings
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
mod.vcc {
	pages = 1
	pages {
		typolink {
			parameter.field = uid
		}
	}

	pages_language_overlay = 1
	pages_language_overlay {
		typolink {
			parameter.field = pid
			additionalParams = &L={field:sys_language_uid}
			additionalParams.insertData = 1
		}
	}

	tt_content = 1
	tt_content {
		typolink {
			parameter.field = pid
			additionalParams = &L={field:sys_language_uid}
			additionalParams.insertData = 1
		}
	}
}
');

if (TYPO3_MODE === 'BE') {

	// Register toolbar item
	//$GLOBALS['TYPO3_CONF_VARS']['typo3/backend.php']['additionalBackendItems'][] = $vccPath . 'registerToolbarItem.php';
	// Register AJAX calls
	//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler('VccBackendController::renderMenu', 'CPSIT\\Vcc\\Controller\\VccBackendController->renderAjax');
	//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler('VccBackendController::closeDocument', 'CPSIT\\Vcc\\Controller\\VccBackendController->closeDocument');
	// Register update signal to update the number of open documents
	//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['updateSignalHook']['OpendocsController::updateNumber'] = 'TCPSIT\\Vcc\\Controller\\VccBackendController->updateNumberOfOpenDocsHook';

	// Only add If
}

// Register additional clear cache menu item
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions']['vccFlushCache'] = 'CPSIT\\Vcc\\Toolbar\\ToolbarItem';

// Register AJAX calls
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
	'VccBackendController::flushCache',
	'CPSIT\\Vcc\\Controller\\VccBackendController->flushCacheAction'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
	'VccBackendController::banCache',
	'CPSIT\\Vcc\\Controller\\VccBackendController->banCacheAction'
);

// Register hook to add the cache clear button to configured items in different views
if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(
		\TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version()) < 7006000
) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/template.php']['docHeaderButtonsHook']['Vcc'] =
		'CPSIT\\Vcc\\Hook\\DocHeaderButtonsHook->addButton';

} else {
	// https://wiki.typo3.org/TYPO3.CMS/Releases/7.6/Feature#ButtonBar_Hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook']['Vcc'] =
        \CPSIT\Vcc\Hook\ButtonBarHook::class . '->getButtons';
}

// Initialize array for internal hooks
if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['vcc']['hooks']['communicationService'])) {
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['vcc']['hooks']['communicationService'] = array();
}