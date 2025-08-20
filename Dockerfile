FROM php:7.4-apache
RUN a2enmod rewrite
WORKDIR /var/www/html
COPY . /var/www/html
RUN sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/dist/web#g' /etc/apache2/sites-available/000-default.conf && printf "<Directory /var/www/html/dist/web>\nAllowOverride All\nRequire all granted\n</Directory>\n" >> /etc/apache2/apache2.conf
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN if [ ! -d "/var/www/html/dist/vendor" ]; then cd dist && composer install --no-interaction --prefer-dist; fi
EXPOSE 80
CMD ["apache2-foreground"]
