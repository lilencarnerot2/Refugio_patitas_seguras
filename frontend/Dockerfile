FROM php:8.2-cli

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

USER www-data

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD php -r '$f=@file_get_contents("http://127.0.0.1:8080/"); exit($f === false ? 1 : 0);'

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]