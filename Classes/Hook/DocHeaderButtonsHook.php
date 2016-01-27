<?php
namespace CPSIT\Vcc\Hook;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Nicole Cordes <cordes@cps-it.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \CPSIT\Vcc\Service\CommunicationService;
use \CPSIT\Vcc\Service\TsConfigService;
use \TYPO3\CMS\Backend\Utility\BackendUtility;
use \TYPO3\CMS\Backend\Utility\IconUtility;
use \TYPO3\CMS\Core\Html\HtmlParser;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Adds the cache clear button to the edit form
 *
 * @author Nicole Cordes <cordes@cps-it.de>
 * @package TYPO3
 * @subpackage vcc
 */
class DocHeaderButtonsHook
{

    /**
     * @var \TYPO3\CMS\Backend\Template\DocumentTemplate|null
     */
    protected $pObj = null;

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var string
     */
    protected $permsClause = '';

    /**
     * @var \CPSIT\Vcc\Service\TsConfigService|null
     */
    protected $tsConfigService = null;

    /**
     * Initialize the object
     *
     */
    public function __construct()
    {

        /** @var \CPSIT\Vcc\Service\TsConfigService $tsConfigService */
        $tsConfigService = GeneralUtility::makeInstance('CPSIT\Vcc\Service\TsConfigService');
        $this->injectTsConfigService($tsConfigService);

        $this->permsClause = $GLOBALS['BE_USER']->getPagePermsClause(2);
    }

    /**
     * Injects the TSConfig service
     *
     * @param \CPSIT\Vcc\Service\TsConfigService $tsConfigService
     *
     * @return void
     */
    public function injectTsConfigService(TsConfigService $tsConfigService)
    {
        $this->tsConfigService = $tsConfigService;
    }

    /**
     * Checks access to the record and adds the clear cache button
     *
     * @param array $params
     * @param \TYPO3\CMS\Backend\Template\DocumentTemplate $pObj
     *
     * @return void
     */
    public function addButton($params, $pObj)
    {
        $this->params = $params;
        $this->pObj = $pObj;

        // Add JS
        $pObj->getPageRenderer()->addJsFile(
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('vcc') . 'Resources/Public/JavaScript/Vcc.js'
        );

        $record = array();
        $table = '';

        // For web -> page view or web -> list view
        if ($this->pObj->scriptID === 'ext/cms/layout/db_layout.php' || $this->pObj->scriptID === 'ext/recordlist/mod1/index.php') {
            $id = GeneralUtility::_GP('id');
            if (is_object($GLOBALS['SOBE']) && $GLOBALS['SOBE']->current_sys_language) {
                $table = 'pages_language_overlay';
                $record = BackendUtility::getRecordsByField($table, 'pid', $id, ' AND ' . $table . '.sys_language_uid=' . intval($GLOBALS['SOBE']->current_sys_language), '', '', '1');
                if (is_array($record) && !empty($record)) {
                    $record = $record[0];
                }
            } else {
                $table = 'pages';
                $record = array(
                    'uid' => $id,
                    'pid' => $id
                );
            }
        } elseif ($this->pObj->scriptID === 'typo3/alt_doc.php') { // For record edit
            $editConf = GeneralUtility::_GP('edit');
            if (is_array($editConf) && !empty($editConf)) {
                // Finding the current table
                reset($editConf);
                $table = key($editConf);

                // Finding the first id and get the records pid
                reset($editConf[$table]);
                $recordUid = key($editConf[$table]);
                // If table is pages we need uid (as pid) to get TSconfig
                if ($table === 'pages') {
                    $record['uid'] = $recordUid;
                    $record['pid'] = $recordUid;
                } else {
                    $record = BackendUtility::getRecord($table, $recordUid, 'uid, pid');
                }
            }
        }

        if (isset($record['pid']) && $record['pid'] > 0) {
            if ($this->isModuleAccessible($record['pid'], $table)) {

                // Process last request
                //$button = $this->process($table, $record['uid']);
                $button = '';
                // Generate button with form for list view
                if ($this->pObj->scriptID === 'ext/recordlist/mod1/index.php') {
                    $button .= $this->generateButton($table, $record, true);
                } else { // Generate plain input button
                    $button .= $this->generateButton($table, $record);
                }

                // Add button to button list and extend layout
                $this->params['buttons']['vcc'] = $button;
                $buttonWrap = HtmlParser::getSubpart($pObj->moduleTemplate, '###BUTTON_GROUP_WRAP###');
                $this->params['markers']['BUTTONLIST_LEFT'] .= HtmlParser::substituteMarker($buttonWrap, '###BUTTONS###', trim($button));
            }
        }
    }

    /**
     * Returns the icon button on condition wrapped with a form
     *
     * @param boolean $wrapWithForm
     *
     * @return string
     */
    protected function generateButton($table, $record, $wrapWithForm = false)
    {
        $url = BackendUtility::getAjaxUrl('VccBackendController::banCache', ['table' => $table, 'record' => $record]);

        $html = '<a href="#" onclick="Vcc.process(this, \'' . $url . '\'); return false;" title="Clear Varnish cache">'
            . IconUtility::getSpriteIcon('extensions-vcc-clearVarnishCache')
            . '</a>';

        return $html;
    }

    /**
     * Checks if the button could be inserted
     *
     * @param integer $pageId
     * @param string $table
     *
     * @return boolean
     */
    protected function isModuleAccessible($pageId, $table)
    {
        $access = false;

        // Check edit rights for page as cache can be flushed then only
        $pageinfo = BackendUtility::readPageAccess($pageId, $this->permsClause);
        if ($pageinfo !== false) {


            // Don not allow sys_folders
            $denyPagesTypes = [254, 255, 3];

            if(!empty($denyPagesTypes) && 'pages' == $table && in_array($pageinfo['doktype'], $denyPagesTypes)) {
                $access = false;

            } else {
                // Get TSconfig for extension
                $tsConfig = $this->tsConfigService->getConfiguration($pageId);
                if (isset($tsConfig[$table]) && !empty($tsConfig[$table])) {
                    $access = true;
                }
            }
        }

        return $access;
    }

}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/vcc/Classes/Hook/DocHeaderButtonsHook.php']) {
    include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/vcc/Classes/Hook/DocHeaderButtonsHook.php']);
}
