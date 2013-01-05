<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

	// Register sprite icons
$icons = array(
	'clearVarnishCache' => t3lib_extMgm::extRelPath('vcc') . 'Resources/Public/Icons/CachePlugin.png',
);
t3lib_SpriteManager::addSingleIcons($icons, 'vcc');

	// Add default module settings
t3lib_extMgm::addPageTSConfig('
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

?>