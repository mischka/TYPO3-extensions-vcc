<?php
namespace CPSIT\Vcc\Hook;

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

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ButtonBarHook
 * @package CPSIT\Vcc\Hook
 */
class ButtonBarHook
{
    /**
     * Get buttons
     *
     * @param array $params
     * @param ButtonBar $buttonBar
     * @return array
     */
    public function getButtons(array $params, ButtonBar $buttonBar)
    {
        $buttons = $params['buttons'];

        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $button = $buttonBar->makeLinkButton();
        $button->setIcon($iconFactory->getIcon('extensions-vcc-clearVarnishCache', Icon::SIZE_SMALL));
        $button->setTitle('Clear Varnish cache');
        //$button->setOnClick('alert(&quot;Hook works&quot;);return false;');
        $button->setHref(BackendUtility::getAjaxUrl('VccBackendController::banCache'));

        $buttons[ButtonBar::BUTTON_POSITION_LEFT][1][] = $button;

        return $buttons;
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
}