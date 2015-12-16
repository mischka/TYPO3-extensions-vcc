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

use \CPSIT\Vcc\Hook\CommunicationServiceHookInterface;
use \TYPO3\CMS\Backend\Utility\BackendUtility;
use \TYPO3\CMS\Core\Messaging\FlashMessage;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Frontend\Page\PageGenerator;

/**
 * Service to send the cache command to server
 *
 * @author Nicole Cordes <cordes@cps-it.de>
 * @package TYPO3
 * @subpackage vcc
 */
class CommunicationService implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer|null
     */
    protected $contentObject = null;

    /**
     * @var boolean
     */
    protected $enableIndexScript = false;

    /**
     * @var \CPSIT\Vcc\Service\ExtensionSettingService|null
     */
    protected $extensionSettingService = null;

    /**
     * @var array
     */
    protected $hookObjects = array();

    /**
     * @var string
     */
    protected $httpMethod = '';

    /**
     * @var string
     */
    protected $httpProtocol = '';

    /**
     * @var \CPSIT\Vcc\Service\LoggingService|null
     */
    protected $loggingService = null;

    /**
     * @var array
     */
    protected $serverArray = array();

    /**
     * @var boolean
     */
    protected $stripSlash = false;

    /**
     * @var \CPSIT\Vcc\Service\TsConfigService|null
     */
    protected $tsConfigService = null;

    /**
     * Initialize the object
     */
    public function __construct()
    {
        /** @var \CPSIT\Vcc\Service\ExtensionSettingService $extensionSettingService */
        $extensionSettingService = GeneralUtility::makeInstance('CPSIT\\Vcc\\Service\\ExtensionSettingService');
        $this->injectExtensionSettingService($extensionSettingService);

        /** @var \CPSIT\Vcc\Service\LoggingService $loggingService */
        $loggingService = GeneralUtility::makeInstance('CPSIT\\Vcc\\Service\\LoggingService');
        $this->injectLoggingService($loggingService);

        /** @var \CPSIT\Vcc\Service\TsConfigService $tsConfigService */
        $tsConfigService = GeneralUtility::makeInstance('CPSIT\\Vcc\\Service\\TsConfigService');
        $this->injectTsConfigService($tsConfigService);

        $configuration = $this->extensionSettingService->getConfiguration();
        $this->serverArray = GeneralUtility::trimExplode(',', $configuration['server'], 1);
        $this->httpMethod = trim($configuration['httpMethod']);
        $this->httpProtocol = trim($configuration['httpProtocol']);
        $this->stripSlash = $configuration['stripSlash'];
        $this->enableIndexScript = $configuration['enableIndexScript'];

        if (!is_object($GLOBALS['TSFE'])) {
            $this->createTSFE();
        }

        /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer contentObject */
        $this->contentObject = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

        // Initialize hook objects
        $this->initializeHookObjects();
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
     * Injects the logging service
     *
     * @param \CPSIT\Vcc\Service\LoggingService $loggingService
     *
     * @return void
     */
    public function injectLoggingService(LoggingService $loggingService)
    {
        $this->loggingService = $loggingService;
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
     *
     */
    protected function initializeHookObjects()
    {
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['vcc']['hooks']['communicationService'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['vcc']['hooks']['communicationService'] as $classReference) {
                $hookObject = GeneralUtility::getUserObj($classReference);

                // Hook objects have to implement interface
                if ($hookObject instanceof CommunicationServiceHookInterface) {
                    $this->hookObjects[] = $hookObject;
                }
            }
            unset($classReference);
        }
    }

    /**
     * Generates the flash messages for the requests
     *
     * @param array $resultArray
     *
     * @return string
     */
    public function generateBackendMessage(array $resultArray)
    {
        $content = '';

        if (is_array($resultArray)) {
            foreach ($resultArray as $result) {
                switch ($result['status']) {
                    case FlashMessage::OK:
                        // Extend button marker with messages
                        $content .= '<script type="text/javascript">
                            parent.TYPO3.Flashmessage.display(
                                TYPO3.Severity.ok,
                                "Server: ' . $result['server'] . ' // Host: ' . $result['host'] . '",
                                "Request: ' . $result['request'] . '<br />Message: ' . $result['message'][0] . '",
                                5
                            );
                        </script>';
                        break;

                    default:
                        // Extend button marker with messages
                        $content .= '<script type="text/javascript">
                            parent.TYPO3.Flashmessage.display(
                                TYPO3.Severity.error,
                                "Server: ' . $result['server'] . ' // Host: ' . $result['host'] . '",
                                "Request: ' . $result['request'] . '<br />Message: ' . implode('<br />', $result['message']) . '<br />Sent:<br />' . implode('<br />', $result['requestHeader']) . '",
                                10
                            );
                        </script>';
                        break;
                }
            }
            unset($result);
        }

        return $content;
    }

    /**
     * Send clear cache commands for pages to defined server
     *
     * @param string $fileName
     * @param string $host
     * @param boolean $quote
     *
     * @return array
     */
    public function sendClearCacheCommandForFiles($fileName, $host = '', $quote = true)
    {
        // Log debug information
        $logData = array(
            'fileName' => $fileName,
            'host' => $host
        );
        $this->loggingService->debug('CommunicationService::sendClearCacheCommandForFiles arguments', $logData);

        // If no host was given get all
        if ($host === '') {
            $hostArray = array();

            // Get all domain records and check page access
            $domainArray = BackendUtility::getRecordsByField('sys_domain', 'redirectTo', '', ' AND hidden=0');
            if (is_array($domainArray) && !empty($domainArray)) {
                $permsClause = $GLOBALS['BE_USER']->getPagePermsClause(2);
                foreach ($domainArray as $domain) {
                    $pageinfo = BackendUtility::readPageAccess($domain['pid'], $permsClause);
                    if ($pageinfo !== false) {
                        $hostArray[] = $domain['domainName'];
                    }
                }
                unset($domain);
            }
            $host = implode(',', $hostArray);

            // Log debug information
            $logData['host'] = $host;
            $this->loggingService->debug('CommunicationService::sendClearCacheCommandForFiles built host', $logData);
        }

        return $this->processClearCacheCommand($fileName, 0, $host, $quote);
    }

    /**
     * Send clear cache commands for pages to defined server
     *
     * @param string $table
     * @param int $uid
     * @param string $host
     * @param boolean $quote
     *
     * @return array
     */
    public function sendClearCacheCommandForTables($table, $uid, $host = '', $quote = true)
    {
        // Get current record to process
        $record = BackendUtility::getRecord($table, $uid);

        // Build request
        if ($table === 'pages') {
            $pid = $record['uid'];
        } else {
            $pid = $record['pid'];
        }

        // Log debug information
        $logData = array(
            'table' => $table,
            'uid' => $uid,
            'host' => $host,
            'pid' => $pid
        );
        $this->loggingService->debug('CommunicationService::sendClearCacheCommandForTables arguments', $logData, $pid);

        $tsConfig = $this->tsConfigService->getConfiguration($pid);
        $typolink = $tsConfig[$table . '.']['typolink.'];
        $this->contentObject->data = $record;

        $url = $this->contentObject->typoLink_URL($typolink);
        $LD = $this->contentObject->lastTypoLinkLD;

        // Check for root site
        if ($url === '' && $table === 'pages') {
            $rootline = BackendUtility::BEgetRootLine($uid);
            if (is_array($rootline) && count($rootline) > 1) {
                // If uid equals the site root we have to process
                if ($uid == $rootline[1]['uid']) {
                    $url = '/';
                }
            }
        }

        // Log debug information
        $logData['url'] = $url;
        $this->loggingService->debug('CommunicationService::sendClearCacheCommandForTables built url', $logData, $pid);

        // Process only for valid URLs
        if ($url !== '') {

            $url = $this->removeHost($url);
            $responseArray = $this->processClearCacheCommand($url, $pid, $host, $quote);

            // Check support of index.php script
            if ($this->enableIndexScript) {
                $url = $LD['url'] . $LD['linkVars'];
                $url = $this->removeHost($url);

                $indexResponseArray = $this->processClearCacheCommand($url, $pid, $host, $quote);
                $responseArray = array_merge($responseArray, $indexResponseArray);
            }

            return $responseArray;
        }

        return array(
            array(
                'status' => FlashMessage::ERROR,
                'message' => array('No valid URL was generated.', 'table: ' . $table, 'uid: ' . $uid, 'host: ' . $host),
                'requestHeader' => array($url)
            )
        );
    }

    /**
     * Init the TSFE in the Backend to generate PageUrls
     */
    protected function createTSFE()
    {
        if (!is_object($GLOBALS['TT'])) {
            $GLOBALS['TT'] = GeneralUtility::makeInstance('t3lib_TimeTrackNull');
        }

        $GLOBALS['TSFE'] = GeneralUtility::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], 1, '');
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->getCompressedTCarray();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
        if (TYPO3_MODE == 'BE') {
            // Set current backend language
            $GLOBALS['TSFE']->getPageRenderer()->setLanguage($GLOBALS['LANG']->lang);
        }
        $GLOBALS['TSFE']->newcObj();

        PageGenerator::pagegenInit();
    }

    /**
     * Processes the CURL request and sends action to Varnish server
     *
     * @param string $url
     * @param integer $pid
     * @param string $host
     * @param boolean $quote
     *
     * @return array
     */
    protected function processClearCacheCommand($url, $pid, $host = '', $quote = true)
    {
        $responseArray = array();

        foreach ($this->serverArray as $server) {
            $response = array(
                'server' => $server
            );

            // Build request
            if ($this->stripSlash) {
                $url = rtrim($url, '/');
            }
            $request = $server . '/' . ltrim($url, '/');
            $response['request'] = $request;

            // Check for curl functions
            if (!function_exists('curl_init')) {
                // TODO: Implement fallback to file_get_contents()
                $response['status'] = FlashMessage::ERROR;
                $response['message'] = 'No curl_init available';
            } else {
                // If no host was given we need to loop over all
                $hostArray = array();
                if ($host !== '') {
                    $hostArray = GeneralUtility::trimExplode(',', $host, 1);
                } else {
                    // Get all (non-redirecting) domains from root
                    $rootLine = BackendUtility::BEgetRootLine($pid);
                    foreach ($rootLine as $row) {
                        $domainArray = BackendUtility::getRecordsByField('sys_domain', 'pid', $row['uid'], ' AND redirectTo="" AND hidden=0');
                        if (is_array($domainArray) && !empty($domainArray)) {
                            foreach ($domainArray as $domain) {
                                $hostArray[] = $domain['domainName'];
                            }
                            unset($domain);
                        }
                    }
                    unset($row);
                }

                // Fallback to current server
                if (empty($hostArray)) {
                    $domain = rtrim(GeneralUtility::getIndpEnv('TYPO3_SITE_URL'), '/');
                    $hostArray[] = substr($domain, strpos($domain, '://') + 3);
                }

                // Loop over hosts
                foreach ($hostArray as $xHost) {
                    $response['host'] = $xHost;

                    // Curl initialization
                    $ch = curl_init();

                    // Disable direct output
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    // Only get header response
                    curl_setopt($ch, CURLOPT_HEADER, 1);
                    curl_setopt($ch, CURLOPT_NOBODY, 1);

                    // Set url
                    curl_setopt($ch, CURLOPT_URL, $request);

                    // Set method and protocol
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->httpMethod);
                    curl_setopt($ch, CURLOPT_HTTP_VERSION, ($this->httpProtocol === 'http_10') ? CURL_HTTP_VERSION_1_0 : CURL_HTTP_VERSION_1_1);

                    // Set X-Host and X-Url header
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'X-Host: ' . (($quote) ? preg_quote($xHost) : $xHost),
                        'X-Url: ' . (($quote) ? preg_quote($url) : $url),
                    ));

                    // Store outgoing header
                    curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

                    // Include preProcess hook (e.g. to set some alternative curl options
                    foreach ($this->hookObjects as $hookObject) {
                        /** @var \CPSIT\Vcc\Hook\CommunicationServiceHookInterface $hookObject */
                        $hookObject->preProcess($ch, $request, $response, $this);
                    }
                    unset($hookObject);

                    $header = curl_exec($ch);
                    if (!curl_error($ch)) {
                        $response['status'] = (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) ? FlashMessage::OK : FlashMessage::ERROR;
                        $response['message'] = preg_split('/(\r|\n)+/m', trim($header));
                    } else {
                        $response['status'] = FlashMessage::ERROR;
                        $response['message'] = array(curl_error($ch));
                    }
                    $response['requestHeader'] = preg_split('/(\r|\n)+/m', trim(curl_getinfo($ch, CURLINFO_HEADER_OUT)));

                    // Include postProcess hook (e.g. to start some other jobs)
                    foreach ($this->hookObjects as $hookObject) {
                        /** @var \CPSIT\Vcc\Hook\CommunicationServiceHookInterface $hookObject */
                        $hookObject->postProcess($ch, $request, $response, $this);
                    }
                    unset($hookObject);

                    curl_close($ch);

                    // Log debug information
                    $logData = array(
                        'url' => $url,
                        'pid' => $pid,
                        'host' => $host,
                        'response' => $response
                    );
                    $logType = ($response['status'] == FlashMessage::OK) ? LoggingService::OK : LoggingService::ERROR;
                    $this->loggingService->log('CommunicationService::processClearCacheCommand', $logData, $logType, $pid, 3);

                    $responseArray[] = $response;
                }
                unset($xHost);
            }
        }
        unset($server);

        return $responseArray;
    }

    /**
     * Remove any host from an url
     *
     * @param string $url
     *
     * @return string
     */
    protected function removeHost($url)
    {
        if (strpos($url, '://') !== false) {
            $urlArray = parse_url($url);
            $url = substr($url, strlen($urlArray['scheme'] . '://' . $urlArray['host']));
        }

        return $url;
    }
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/vcc/Classes/Service/CommunicationService.php']) {
    include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/vcc/Classes/Service/CommunicationService.php']);
}
