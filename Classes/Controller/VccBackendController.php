<?php
namespace CPSIT\Vcc\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c)
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

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use \TYPO3\CMS\Backend\Utility\IconUtility;
use \TYPO3\CMS\Core\Messaging\FlashMessage;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

use \CPSIT\Vcc\Service\CommunicationService;
use \CPSIT\Vcc\Service\TsConfigService;

/**
 * Class VccBackendController
 * @package CPSIT\Vcc\Controller
 *
 * @see http://stackoverflow.com/questions/25197920/how-do-i-add-own-buttons-in-typo3-backend-to-call-extension-method
 * @see \TYPO3\CMS\SysAction\ActionToolbarMenu
 * @see \TYPO3\CMS\Opendocs\Controller\OpendocsController
 */
class VccBackendController
{
    protected $extensionKey = 'vcc';

    /**
     * @var \TYPO3\CMS\Backend\Controller\BackendController
     */
    protected $backendReference;

    /**
     * @var \CPSIT\Vcc\Service\CommunicationService|null
     */
    protected $communicationService = null;

    /**
     * @var \CPSIT\Vcc\Service\TsConfigService|null
     */
    protected $tsConfigService = null;

    /**
     * Constructor that receives a back reference to the backend
     *
     * @param \TYPO3\CMS\Backend\Controller\BackendController $backendReference TYPO3 backend object reference
     */
    public function __construct(\TYPO3\CMS\Backend\Controller\BackendController &$backendReference = null)
    {
        //parent::__construct($backendReference);
        $this->backendReference = $backendReference;

        /** @var \CPSIT\Vcc\Service\CommunicationService $communicationService */
        $communicationService = GeneralUtility::makeInstance('CPSIT\Vcc\Service\CommunicationService');
        $this->injectCommunicationService($communicationService);

        /** @var \CPSIT\Vcc\Service\TsConfigService $tsConfigService */
        $tsConfigService = GeneralUtility::makeInstance('CPSIT\Vcc\Service\TsConfigService');
        $this->injectTsConfigService($tsConfigService);

        $this->permsClause = $GLOBALS['BE_USER']->getPagePermsClause(2);
    }

    /**
     * Injects the communication service
     *
     * @param \CPSIT\Vcc\Service\CommunicationService $communicationService
     *
     * @return void
     */
    public function injectCommunicationService(CommunicationService $communicationService)
    {
        $this->communicationService = $communicationService;
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
     * Checks whether the user has access to this toolbar item
     *
     * @return boolean TRUE if user has access, FALSE if not
     */
    public function checkAccess()
    {
        // TODO: Implement checkAccess() method.
        //$this->
        return true;
    }

    /**
     *
     *
     * @see https://docs.typo3.org/typo3cms/CoreApiReference/JavaScript/Ajax/Backend/Index.html
     *
     * @param array $params Array of parameters from the AJAX interface, currently unused
     * @param \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj Object of type AjaxRequestHandler
     * @return void
     */
    public function flushCacheAction($params = array(), \TYPO3\CMS\Core\Http\AjaxRequestHandler &$ajaxObj = null)
    {
        //$menuContent = $this->renderMenu();
        //var_dump($ajaxObj, $params);

        // Get All Domain records with enabled if set
        // Ban with wildcard if set


    }

    /**
     *
     *
     * @see https://docs.typo3.org/typo3cms/CoreApiReference/JavaScript/Ajax/Backend/Index.html
     *
     * @param array $params Array of parameters from the AJAX interface, currently unused
     * @param \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj Object of type AjaxRequestHandler
     * @return void
     */
    public function banCacheAction($params = array(), \TYPO3\CMS\Core\Http\AjaxRequestHandler &$ajaxObj = null)
    {
        $table = GeneralUtility::_GP('table');
        $record = GeneralUtility::_GP('record');
        $uid = $record['uid'];

        if (empty($table) || empty($uid)) {

            $ajaxObj->setError('No Table or Uid found!');

        } else {

            $results = $this->communicationService->sendClearCacheCommandForTables($table, $uid);

            $ajaxObj->addContent('results', $results);
            $ajaxObj->setContentFormat('json');
        }
    }
}