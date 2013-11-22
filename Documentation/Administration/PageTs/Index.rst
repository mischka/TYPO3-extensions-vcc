.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

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
