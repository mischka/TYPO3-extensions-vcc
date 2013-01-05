# Enable flushing access only to defined ip addresses
acl flushers {
	"127.0.0.1";
}

sub vcl_recv {
	if (req.request != "GET" &&
		req.request != "HEAD" &&
		req.request != "PUT" &&
		req.request != "POST" &&
		req.request != "TRACE" &&
		req.request != "OPTIONS" &&
		req.request != "DELETE") {

		# Add BAN request for acl flushers
		if (req.request == "BAN") {
			if (client.ip ~ flushers) {
				if (req.http.X-Host) {
					ban("req.http.host == " + req.http.X-Host + " && req.url ~ " + req.url + "[/]?(\?.*)?$");
					error 200 "OK";
				} else {
					error 400 "Bad Request";
				}
			} else {
				error 405 "Not allowed";
			}
		} else {
			/* Non-RFC2616 or CONNECT which is weird. */
			return (pipe);
		}
	}
}