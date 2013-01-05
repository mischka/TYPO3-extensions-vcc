<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "vcc".
 *
 * Auto generated 03-01-2013 09:11
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
			'typo3' => '4.5.0-4.7.99',
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
	'_md5_values_when_last_written' => 'a:54:{s:12:"ext_icon.gif";s:4:"402e";s:17:"ext_localconf.php";s:4:"c217";s:14:"ext_tables.php";s:4:"2cb5";s:14:"ext_tables.sql";s:4:"f285";s:21:"ExtensionBuilder.json";s:4:"ebfe";s:38:"Classes/Controller/ImageController.php";s:4:"7006";s:33:"Classes/Domain/Model/Category.php";s:4:"44eb";s:30:"Classes/Domain/Model/Image.php";s:4:"3af2";s:48:"Classes/Domain/Repository/CategoryRepository.php";s:4:"d78d";s:45:"Classes/Domain/Repository/ImageRepository.php";s:4:"2bc2";s:42:"Classes/ViewHelpers/VsprintfViewHelper.php";s:4:"f027";s:51:"Classes/ViewHelpers/Image/OrientationViewHelper.php";s:4:"c9b8";s:49:"Classes/ViewHelpers/Widget/PaginateViewHelper.php";s:4:"a508";s:60:"Classes/ViewHelpers/Widget/Controller/PaginateController.php";s:4:"756c";s:44:"Configuration/ExtensionBuilder/settings.yaml";s:4:"9825";s:41:"Configuration/FlexForms/ImageDatabase.xml";s:4:"03a8";s:30:"Configuration/TCA/Category.php";s:4:"6fac";s:27:"Configuration/TCA/Image.php";s:4:"3058";s:38:"Configuration/TypoScript/constants.txt";s:4:"e2ae";s:34:"Configuration/TypoScript/setup.txt";s:4:"459d";s:40:"Resources/Private/Language/locallang.xml";s:4:"a3b6";s:43:"Resources/Private/Language/locallang_be.xml";s:4:"3b39";s:80:"Resources/Private/Language/locallang_csh_tx_bmuimagedb_domain_model_category.xml";s:4:"a9ee";s:77:"Resources/Private/Language/locallang_csh_tx_bmuimagedb_domain_model_image.xml";s:4:"ba09";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"b50d";s:50:"Resources/Private/Language/locallang_flexforms.xml";s:4:"037a";s:38:"Resources/Private/Layouts/Default.html";s:4:"9ca6";s:41:"Resources/Private/Partials/Links/Add.html";s:4:"8bf8";s:48:"Resources/Private/Partials/Links/Collection.html";s:4:"7c67";s:42:"Resources/Private/Partials/Links/List.html";s:4:"2853";s:47:"Resources/Private/Partials/Links/Icons/Add.html";s:4:"e71b";s:50:"Resources/Private/Partials/Links/Icons/Delete.html";s:4:"5a61";s:52:"Resources/Private/Partials/Links/Icons/Download.html";s:4:"c026";s:48:"Resources/Private/Partials/Links/Icons/Show.html";s:4:"7283";s:42:"Resources/Private/Partials/List/Image.html";s:4:"722b";s:42:"Resources/Private/Templates/Image/Add.html";s:4:"712d";s:49:"Resources/Private/Templates/Image/Collection.html";s:4:"a3fc";s:43:"Resources/Private/Templates/Image/List.html";s:4:"e87e";s:43:"Resources/Private/Templates/Image/Show.html";s:4:"5bc5";s:66:"Resources/Private/Templates/ViewHelpers/Widget/Paginate/Index.html";s:4:"aa05";s:42:"Resources/Public/Icons/2012_link_arrow.png";s:4:"4849";s:38:"Resources/Public/Icons/bdb_ansicht.gif";s:4:"14f8";s:39:"Resources/Public/Icons/bdb_bild_300.gif";s:4:"5806";s:38:"Resources/Public/Icons/bdb_bild_72.gif";s:4:"7a0b";s:39:"Resources/Public/Icons/bdb_loeschen.gif";s:4:"10d4";s:39:"Resources/Public/Icons/bdb_sammlung.gif";s:4:"a89a";s:41:"Resources/Public/Icons/bdb_uebersicht.gif";s:4:"4ad2";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:62:"Resources/Public/Icons/tx_bmuimagedb_domain_model_category.gif";s:4:"e117";s:59:"Resources/Public/Icons/tx_bmuimagedb_domain_model_image.gif";s:4:"d556";s:44:"Resources/Public/Stylesheets/bmu_imagedb.css";s:4:"7078";s:45:"Tests/Unit/Controller/ImageControllerTest.php";s:4:"79c5";s:37:"Tests/Unit/Domain/Model/ImageTest.php";s:4:"8c7c";s:14:"doc/manual.sxw";s:4:"8d2d";}',
	'suggests' => array(
	),
	'conflicts' => '',
);

?>