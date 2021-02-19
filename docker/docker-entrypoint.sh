#!/bin/sh
php /var/www/html/artisan wait_db_alive && php /var/www/html/artisan migrate && /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
