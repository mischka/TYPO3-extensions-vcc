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

/**
 * Class VccBackendController
 * @package CPSIT\Vcc\Controller
 *
 * @see http://stackoverflow.com/questions/25197920/how-do-i-add-own-buttons-in-typo3-backend-to-call-extension-method
 */
class VccBackendController implements \TYPO3\CMS\Backend\Toolbar\ToolbarItemHookInterface
{
    protected $EXTKEY = 'vcc';

    /**
     * Constructor that receives a back reference to the backend
     *
     * @param \TYPO3\CMS\Backend\Controller\BackendController $backendReference TYPO3 backend object reference
     */
    public function __construct(\TYPO3\CMS\Backend\Controller\BackendController &$backendReference = null)
    {
        parent::__construct($backendReference);
    }

    /**
     * Checks whether the user has access to this toolbar item
     *
     * @return boolean TRUE if user has access, FALSE if not
     */
    public function checkAccess()
    {
        // TODO: Implement checkAccess() method.
    }

    /**
     * Renders the toolbar item
     *
     * @return string The toolbar item rendered as HTML string
     */
    public function render()
    {
        // TODO: Implement render() method.
    }

    /**
     * Returns additional attributes for the list item in the toolbar
     *
     * @return string List item HTML attibutes
     */
    public function getAdditionalAttributes()
    {
        // TODO: Implement getAdditionalAttributes() method.
    }
}