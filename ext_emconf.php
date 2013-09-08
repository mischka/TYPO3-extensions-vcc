<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "vcc".
 *
 * Auto generated 04-11-2013 09:19
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
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'dependencies' => 'cms',
	'module' => '',
	'state' => 'stable',
	'version' => '1.0.0',
	'clearCacheOnLoad' => 0,
	'createDirs' => '',
	'internal' => '',
	'lockType' => '',
	'modify_tables' => '',
	'priority' => '',
	'shy' => '',
	'uploadfolder' => 0,
	'_md5_values_when_last_written' => 'a:15:{s:9:"ChangeLog";s:4:"6855";s:16:"ext_autoload.php";s:4:"1c64";s:21:"ext_conf_template.txt";s:4:"8b8d";s:12:"ext_icon.gif";s:4:"7736";s:17:"ext_localconf.php";s:4:"9f06";s:14:"ext_tables.php";s:4:"816b";s:14:"ext_tables.sql";s:4:"d9bc";s:37:"Classes/Hook/DocHeaderButtonsHook.php";s:4:"9e76";s:55:"Classes/Interface/CommunicationServiceHookInterface.php";s:4:"1383";s:40:"Classes/Service/CommunicationService.php";s:4:"1d32";s:43:"Classes/Service/ExtensionSettingService.php";s:4:"3306";s:34:"Classes/Service/LoggingService.php";s:4:"b6f3";s:35:"Classes/Service/TsConfigService.php";s:4:"65b9";s:25:"Documentation/vcc_ban.vcl";s:4:"4911";s:38:"Resources/Public/Icons/CachePlugin.png";s:4:"4312";}',
	'suggests' => array(
	),
	'conflicts' => '',
);

?>