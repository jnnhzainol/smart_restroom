#!/bin/sh
set -eu
php /var/www/html/deployment/init-db.php
exec apache2-foreground
