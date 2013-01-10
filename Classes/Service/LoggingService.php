<?php
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

/**
 * Service to log requests and responses
 *
 * @author Nicole Cordes <cordes@cps-it.de>
 * @package TYPO3
 * @subpackage vcc
 */
class tx_vcc_service_loggingService implements t3lib_Singleton {

	const OK      = 0;
	const NOTICE  = 1;
	const INFO    = 2;
	const WARNING = 3;
	const ERROR   = 4;
	const DEBUG   = 99;

	/**
	 * @var tx_vcc_service_extensionSettingService|NULL
	 */
	var $extensionSettingService = NULL;

	/**
	 * @var integer
	 */
	var $debug = 0;

	/**
	 * @var string
	 */
	var $hash = '';

	/**
	 * Initialize the object
	 *
	 * @return void
	 */
	public function __construct() {
		$extensionSettingService = t3lib_div::makeInstance('tx_vcc_service_extensionSettingService');
		$this->injectExtensionSettingService($extensionSettingService);

		$configuration = $this->extensionSettingService->getConfiguration();
		$this->debug = $configuration['debug'];

		$this->hash = md5(uniqid('LoggingService', TRUE));
	}

	/**
	 * Injects the extension setting service
	 *
	 * @param tx_vcc_service_extensionSettingService $extensionSettingService
	 *
	 * @return void
	 */
	public function injectExtensionSettingService(tx_vcc_service_extensionSettingService $extensionSettingService) {
		$this->extensionSettingService = $extensionSettingService;
	}

	/**
	 * @return void
	 */
	public function log($message, $logData = array(), $type = self::INFO, $pid = 0, $callerDepth = 2, $caller = NULL) {
			// Get caller if not already set
		if ($caller === NULL) {
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
	}

	/**
	 * @return void
	 */
	public function debug($message, $logData = array(), $pid = 0, $callerDepth = 2, $caller = NULL) {
		if ($this->debug) {
				// Adjust callerDepth due to debug function
			$callerDepth++;
			$this->log($message, $logData, self::DEBUG, $pid, $callerDepth, $caller);
		}
	}

	/**
	 * @param integer $callerDepth
	 *
	 * @return array
	 */
	protected function getCallerFromBugtrace($callerDepth) {
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

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/vcc/Classes/Service/LoggingService.php'])  {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/vcc/Classes/Service/LoggingService.php']);
}

?>