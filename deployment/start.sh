#!/bin/sh
set -eu
a2dismod mpm_event mpm_worker
a2enmod mpm_prefork
apache2ctl -t
php /var/www/html/deployment/init-db.php
exec apache2-foreground
