<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "vcc".
 *
 * Auto generated 22-11-2013 01:19
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
	'version' => '1.0.2',
	'clearCacheOnLoad' => 0,
	'createDirs' => '',
	'internal' => '',
	'lockType' => '',
	'modify_tables' => '',
	'priority' => '',
	'shy' => '',
	'uploadfolder' => 0,
	'_md5_values_when_last_written' =>
		'a:32:{s:9:"ChangeLog";s:4:"e3be";s:16:"ext_autoload.php";s:4:"1c64";s:21:"ext_conf_template.txt";s:4:"8b8d";s:12:"ext_icon.gif";s:' .
		'4:"7736";s:17:"ext_localconf.php";s:4:"9f06";s:14:"ext_tables.php";s:4:"816b";s:14:"ext_tables.sql";s:4:"d9bc";s:37:"Classes/Hook/' .
		'DocHeaderButtonsHook.php";s:4:"ceec";s:55:"Classes/Interface/CommunicationServiceHookInterface.php";s:4:"1383";s:40:"Classes/Servi' .
		'ce/CommunicationService.php";s:4:"6486";s:43:"Classes/Service/ExtensionSettingService.php";s:4:"c1c7";s:34:"Classes/Service/Loggin' .
		'gService.php";s:4:"0010";s:35:"Classes/Service/TsConfigService.php";s:4:"a923";s:25:"Documentation/default.vcl";s:4:"2042";s:26:"D' .
		'ocumentation/Includes.txt";s:4:"cd93";s:23:"Documentation/Index.rst";s:4:"68e5";s:26:"Documentation/Settings.yml";s:4:"3727";s:38:' .
		'"Documentation/Administration/Index.rst";s:4:"ae09";s:61:"Documentation/Administration/ExtensionConfiguration/Index.rst";s:4:"56b9' .
		'";s:51:"Documentation/Administration/Installation/Index.rst";s:4:"ecdc";s:45:"Documentation/Administration/PageTs/Index.rst";s:4:"' .
		'0f1e";s:59:"Documentation/Administration/VarnishConfiguration/Index.rst";s:4:"8b74";s:64:"Documentation/Images/Introduction/Screen' .
		'shots/IconInEditView.png";s:4:"71dc";s:63:"Documentation/Images/Introduction/Screenshots/IconInWebView.png";s:4:"9bbd";s:58:"Docum' .
		'entation/Images/Introduction/Screenshots/Messages.png";s:4:"2e60";s:36:"Documentation/Introduction/Index.rst";s:4:"3384";s:48:"Doc' .
		'umentation/Introduction/Screenshots/Index.rst";s:4:"3140";s:44:"Documentation/Introduction/Support/Index.rst";s:4:"bf43";s:49:"Doc' .
		'umentation/Introduction/WhatDoesItDo/Index.rst";s:4:"6f61";s:36:"Documentation/Requirements/Index.rst";s:4:"11ce";s:38:"Resources/' .
		'Public/Icons/CachePlugin.png";s:4:"4312";s:14:"doc/manual.sxw";s:4:"b4b2";}',
	'suggests' => array(
	),
	'conflicts' => '',
);

?>