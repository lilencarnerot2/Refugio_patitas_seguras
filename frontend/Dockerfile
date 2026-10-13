FROM php:8.2-cli

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

USER www-data

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]