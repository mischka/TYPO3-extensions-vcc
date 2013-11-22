.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

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

**For a best practise configuration see** :download:`this default configuration <../default.vcl>`
