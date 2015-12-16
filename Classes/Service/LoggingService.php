<?php
namespace CPSIT\Vcc\Service;

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

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service to log requests and responses
 *
 * @author Nicole Cordes <cordes@cps-it.de>
 * @package TYPO3
 * @subpackage vcc
 */
class LoggingService implements \TYPO3\CMS\Core\SingletonInterface
{

    const DEBUG = 99;

    const ERROR = 4;

    const INFO = 2;

    const NOTICE = 1;

    const OK = 0;

    const WARNING = 3;

    /**
     * @var \CPSIT\Vcc\Service\ExtensionSettingService|null
     */
    protected $extensionSettingService = null;

    /**
     * @var integer
     */
    protected $debug = 0;

    /**
     * @var string
     */
    protected $hash = '';

    /**
     * @var integer
     */
    protected $maxLogAge = 0;

    /**
     * Initialize the object
     */
    public function __construct()
    {
        /** @var \CPSIT\Vcc\Service\ExtensionSettingService $extensionSettingService */
        $extensionSettingService = GeneralUtility::makeInstance('CPSIT\\Vcc\\Service\\ExtensionSettingService');
        $this->injectExtensionSettingService($extensionSettingService);

        $configuration = $this->extensionSettingService->getConfiguration();
        $this->debug = $configuration['debug'];
        $this->maxLogAge = $configuration['maxLogAge'];

        $this->hash = md5(uniqid('LoggingService', true));
    }

    /**
     * Injects the extension setting service
     *
     * @param \CPSIT\Vcc\Service\ExtensionSettingService $extensionSettingService
     *
     * @return void
     */
    public function injectExtensionSettingService(ExtensionSettingService $extensionSettingService)
    {
        $this->extensionSettingService = $extensionSettingService;
    }

    /**
     * @return void
     */
    public function debug($message, $logData = array(), $pid = 0, $callerDepth = 2, $caller = null)
    {
        if ($this->debug) {
            // Adjust callerDepth due to debug function
            $callerDepth++;
            $this->log($message, $logData, self::DEBUG, $pid, $callerDepth, $caller);
        }
    }

    /**
     * @return void
     */
    public function log($message, $logData = array(), $type = self::INFO, $pid = 0, $callerDepth = 2, $caller = null)
    {
        // Get caller if not already set
        if (null === $caller) {
            $caller = $this->getCallerFromBugtrace($callerDepth);
        }

        $insertArray = array(
            'pid' => $pid,
            'tstamp' => time(),
            'be_user' => $GLOBALS['BE_USER']->user['uid'],
            'type' => $type,
            'message' => $message,
            'log_data' => serialize($logData),
            'caller' => serialize($caller),
            'hash' => $this->hash
        );
        $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_vcc_log', $insertArray);

        // Remove old entries
        $month = date('m', time());
        $day = 0 - $this->maxLogAge;
        $year = date('Y', time());
        $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_vcc_log', 'tstamp<' . mktime(0, 0, 0, $month, $day, $year));
    }

    /**
     * @param integer $callerDepth
     *
     * @return array
     */
    protected function getCallerFromBugtrace($callerDepth)
    {
        // Get trace array
        $trace = debug_backtrace();

        // Adjust callerDepth due to separate function
        $callerDepth++;
        if (isset($trace[$callerDepth])) {
            return $trace[$callerDepth];
        }

        return array();
    }
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/vcc/Classes/Service/LoggingService.php']) {
    include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/vcc/Classes/Service/LoggingService.php']);
}
