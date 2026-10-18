FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

#Crea directorio donde se guardaran las fotos de las mascotas y le da permisos al usuario www-data para que pueda escribir en él
RUN mkdir -p /var/www/html/public/uploads/mascotas \
    && chown -R www-data:www-data /var/www/html/public/uploads

#Permitir archivos de hasta 5 MB
RUN printf "upload_max_filesize=5M\npost_max_size=6M\n" \
    > /usr/local/etc/php/conf.d/uploads.ini

USER www-data

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD php -r '$f=@file_get_contents("http://127.0.0.1:8080/?recurso=mascotas"); exit($f === false ? 1 : 0);'

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html/public"]