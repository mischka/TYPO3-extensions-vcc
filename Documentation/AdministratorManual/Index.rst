.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. _admin-manual:

Administrator Manual
====================

Varnish configuration
---------------------

Please add the following configuration to your vcl_recv function in your Varnish configuration file ::

    # Add BAN request
    if (req.request == "BAN") {
        if (req.http.X-Host) {
            ban("req.http.host == " + req.http.X-Host + " && req.url ~ " + req.http.X-Url + "[/]?(\?|&|$)");
            error 200 "OK";
        } else {
            error 400 "Bad Request";
        }
    }

To secure your environment consider to include reset of some server information in vcl_deliver ::

    remove resp.http.Age;
    remove resp.http.Via;
    remove resp.http.X-Powered-By;
    remove resp.http.X-Varnish;

**For a best practise configuration see file** *Documentation/default.vcl*



Installation
------------

To install the extension you just need to enable it in the Extension Manager.

Extension configuration
-----------------------

Varnish Server [basic.server]
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Enter the IP address of your Varnish server. Use a comma seperated list for multiple server support.

HTTP ban method [basic.httpMethod]
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you want to insert an own method which is sent to the Varnish server you can edit it here. Please note that this method has to be equal to the method used in your Varnish configuration file.

HTTP protocol [basic.httpProtocol]
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Set the version of the http protocol which should be used to contact the Varnish server.

Strip slash [basic.stripSlash]
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you use realurl extension (or any other url rendering) with the "appendMissingSlash" configuration you can configure vcc to strip the last slash. This can be useful if you want to customize the BAN handling in your varnish configuration e.g. use regular expressions.

Support index.php script [basic.enableIndexScript]
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you use realurl extension (or any other url rendering) this option enables the cache clearing for alternative
index.php url. This might help your editor to see the latest version if they view the page within the backend.

Maximum age of log entries [basic.maxLogAge]
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

For each action one or multiple log entries (depends on debug setting) are generated in an own table. To minimize the table size you can set a specific age (in days) for the entries.

Debug mode [basic.debug]
^^^^^^^^^^^^^^^^^^^^^^^^

You can enable a detailed logging of the values generated for each action. These information are stored in an own database table as well as send to any devLog extension.

TYPO3 PageTS configuration
--------------------------

You can configure the Varnish Cache Controll extension to be compatible to any data record in the backend. You just need to adopt the PageTS configuration which comes pre defined for pages and content elements. ::

    mod.vcc {
        pages = 1
        pages {
            typolink {
                parameter.field = uid
            }
        }

        pages_language_overlay = 1
        pages_language_overlay {
            typolink {
                parameter.field = pid
                additionalParams = &L={field:sys_language_uid}
                additionalParams.insertData = 1
            }
        }

        tt_content = 1
        tt_content {
            typolink {
                parameter.field = pid
                additionalParams = &L={field:sys_language_uid}
                additionalParams.insertData = 1
            }
        }
    }

Just add a configuration with the name of your database table and a configuration how to generate the link to any (detail) page.