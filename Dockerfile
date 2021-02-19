FROM php:8.0.2-fpm-alpine

RUN apk add --no-cache \
    freetype \
    libpng \
    libjpeg-turbo \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    ffmpeg \
    exiftool \
    && docker-php-ext-configure exif \
    && docker-php-ext-install exif \
    && docker-php-ext-enable exif \
    && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
    docker-php-ext-install -j${NPROC} gd && \
    apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

RUN docker-php-ext-install pdo pdo_mysql

RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 150000M;" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size= 150000M;" >> /usr/local/etc/php/conf.d/uploads.ini

RUN apk add --no-cache \
    supervisor

# Copy supervisor configs
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy docker-entrypoint
COPY ./docker/docker-entrypoint.sh /tmp/docker-entrypoint.sh

ENTRYPOINT ["sh", "/tmp/docker-entrypoint.sh"]
