#!/bin/sh
docker-compose up -d --build tms_wton;
docker exec tms_wton bash -c "composer install;php artisan optimize:clear"
#docker restart nginx;
#docker restart dashboard;