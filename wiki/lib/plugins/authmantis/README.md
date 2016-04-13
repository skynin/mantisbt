"When you update your wiki to the 2013-05-10 “Weatherwax” release, you need an auth plugin for the authentication, because the authentication backends aren't supported anymore."

Mantis integration from here http://www.mantisbt.org/wiki/doku.php/mantisbt:issue:7075:integration_with_dokuwiki uses old "backend auth", so it does not work anymore. This is converter old backend to DocuWiki plugin.

Steps to replace old backend:
  1. Install this plugin - create directory /lib/plugins/authmantis and copy files there
  2. In /config/local/php replace
     $conf['authtype'] = 'mantis';
     with
     $conf['authtype'] = 'authmantis'; 