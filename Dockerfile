FROM fpm-tms

COPY composer.lock composer.json /var/www/tms/

COPY database /var/www/tms/database

WORKDIR /var/www/tms

# RUN php composer.phar install --no-dev --no-scripts
    
COPY . /var/www/tms

RUN chown -R www-data:www-data \
        /var/www/tms/storage \
        /var/www/tms/bootstrap/cache

RUN mv production.env .env
