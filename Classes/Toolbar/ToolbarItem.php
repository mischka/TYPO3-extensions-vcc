<?php
namespace CPSIT\Vcc\Toolbar;

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

use CPSIT\Vcc\Service\ExtensionSettingService;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Class ToolbarItem
 * @package CPSIT\Vcc\Toolbar
 */
class ToolbarItem implements \TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface
{

    static $itemKey = 'vccFlushCache';

    /**
     * @var \CPSIT\Vcc\Service\ExtensionSettingService $extensionSettingService
     * @inject
     */
    protected $extensionSettingService;

    /**
     * Initialize the object
     */
    public function __construct()
    {
        /** @var \CPSIT\Vcc\Service\ExtensionSettingService $extensionSettingService */
        $extensionSettingService = GeneralUtility::makeInstance('CPSIT\\Vcc\\Service\\ExtensionSettingService');
        $this->injectExtensionSettingService($extensionSettingService);
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
     * Adds the flush language cache menu item.
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically used by userTS with options.clearCache.identifier)
     * @return void
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
        if ($this->checkAccess()) {
            $cacheActions[] = array(
                'id'    => self::$itemKey,
                'title' => $this->getLanguageService()->sL('LLL:EXT:vcc/Resources/Private/Language/locallang.xlf:vccFlushCache'),
                'href'  => BackendUtility::getAjaxUrl('VccBackendController::flushCache'),
                'icon'  => IconUtility::getSpriteIcon('extensions-vcc-clearVarnishCache')
            );
            $optionValues[] = self::$itemKey;
        }
    }

    /**
     * Wrapper around the global BE user object.
     *
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * Wrapper around the global language object.
     *
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Check if be_user has access to the button
     *
     * @return bool
     */
    protected function checkAccess()
    {
        $configuration = $this->extensionSettingService->getConfiguration();
        if (isset($configuration['enableClearCacheAction'])
            && true == $configuration['enableClearCacheAction']
        ) {
            return ($this->getBackendUser()->isAdmin() || $this->getBackendUser()->getTSConfigVal('options.clearCache.vcc'));
        }
        return false;
    }
}
