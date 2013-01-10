<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "vcc".
 *
 * Auto generated 05-01-2013 01:26
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Varnish Cache Control',
	'description' => 'Extension to clear Varnish cache on demand',
	'author' => 'Nicole Cordes',
	'author_email' => 'cordes@cps-it.de',
	'author_company' => 'CPS-IT',
	'category' => 'module',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.7.99-4.5.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'dependencies' => 'cms',
	'module' => '',
	'state' => 'alpha',
	'version' => '0.1.0',
	'clearCacheOnLoad' => 0,
	'createDirs' => '',
	'internal' => '',
	'lockType' => '',
	'modify_tables' => '',
	'priority' => '',
	'shy' => '',
	'uploadfolder' => 0,
	'_md5_values_when_last_written' => 'a:13:{s:9:"ChangeLog";s:4:"5054";s:16:"ext_autoload.php";s:4:"aba1";s:21:"ext_conf_template.txt";s:4:"d78b";s:12:"ext_icon.gif";s:4:"7736";s:17:"ext_localconf.php";s:4:"e4a4";s:14:"ext_tables.php";s:4:"158b";s:14:"ext_tables.sql";s:4:"685c";s:37:"Classes/Hook/DocHeaderButtonsHook.php";s:4:"47a9";s:40:"Classes/Service/CommunicationService.php";s:4:"62cb";s:43:"Classes/Service/ExtensionSettingService.php";s:4:"bb3d";s:34:"Classes/Service/LoggingService.php";s:4:"2697";s:35:"Classes/Service/TsConfigService.php";s:4:"e1f2";s:38:"Resources/Public/Icons/CachePlugin.png";s:4:"4312";}',
	'suggests' => array(
	),
	'conflicts' => '',
);

?>