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

Ext.ns('Vcc');

Vcc = {

    /**
     * Check if entry is in list
     */
    process: function(btn, url) {
        var spinner = new Element('span').addClassName('spinner');
        var oldIcon = btn.replace(spinner);

        var processRequest = new Ajax.Request(url, {

            onComplete : function(data) {
                if (data.responseJSON.results) {
                    var results = data.responseJSON.results;
                    for (var i=0; i < results.length; i++) {
                        var result = results[i];
                        parent.TYPO3.Flashmessage.display(
                            ((result.status != 0 ) ? TYPO3.Severity.error : TYPO3.Severity.ok),
                            'Server: ' + result.server + ' // Host: ' + result.host,
                            'Request: ' + result.request + '<br />Message: ' + result.message.join('<br />') + '<br />Sent:<br />' + result.requestHeader.join('<br />') ,
                            10
                        );

                    }
                }
                spinner.replace(btn);
            }.bind(this),
            onT3Error: function(xhr) {
                spinner.replace(btn);
                T3AJAX.showError(xhr);
            }.bind(this)
        });
    }
};
