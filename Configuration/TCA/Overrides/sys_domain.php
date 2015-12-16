<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:vcc/Resources/Private/Language/locallang_db.xlf:';

/**
 * Add extra field showinpreview and some special news controls to sys_file_reference record
 */
$vccSysDomainColumns = array(
    'vcc_enabled' => array(
        'exclude' => 1,
        'label' => $ll . 'sys_domain.vcc_enabled',
        'config' => array(
            'type' => 'check',
            'default' => 0
        )
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_domain', $vccSysDomainColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_domain', 'vcc_enabled', '', 'after:forced');
