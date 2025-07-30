# Use Ubuntu as the base image
FROM ubuntu:22.04

LABEL maintainer="Your Name <you@example.com>"

ENV DEBIAN_FRONTEND=noninteractive

# ---------------------------------------------------------
# 1. Install system packages, add PHP repo (ondrej), and install PHP 8.2 + Composer + Nginx
# ---------------------------------------------------------
RUN apt-get update && apt-get install -y software-properties-common \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update && apt-get install -y \
       php8.2-fpm \
       php8.2-cli \
       php8.2-zip \
       php8.2-curl \
       php8.2-mysql \
       php8.2-xml \
       php8.2-mbstring \
       php8.2-bcmath \
       php8.2-pgsql \
       php8.2-opcache \ 
       nginx \
       git \
       curl \
       zip \
       unzip \
    && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------
# 2. Optimize PHP performance: Enable OPcache
# ---------------------------------------------------------
RUN echo "opcache.enable=1" >> /etc/php/8.2/fpm/php.ini \
    && echo "opcache.enable_cli=1" >> /etc/php/8.2/fpm/php.ini \
    && echo "opcache.memory_consumption=256" >> /etc/php/8.2/fpm/php.ini \
    && echo "opcache.interned_strings_buffer=16" >> /etc/php/8.2/fpm/php.ini \
    && echo "opcache.max_accelerated_files=100000" >> /etc/php/8.2/fpm/php.ini \
    && echo "opcache.validate_timestamps=0" >> /etc/php/8.2/fpm/php.ini \
    && echo "opcache.save_comments=1" >> /etc/php/8.2/fpm/php.ini \
    && echo "opcache.fast_shutdown=1" >> /etc/php/8.2/fpm/php.ini

# ---------------------------------------------------------
# 3. Optimize PHP-FPM settings for high performance
# ---------------------------------------------------------
RUN sed -i 's/pm.max_children = 5/pm.max_children = 20/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/pm.start_servers = 2/pm.start_servers = 5/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/pm.min_spare_servers = 1/pm.min_spare_servers = 3/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/pm.max_spare_servers = 3/pm.max_spare_servers = 10/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.process_idle_timeout = 10s/pm.process_idle_timeout = 10s/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.max_requests = 500/pm.max_requests = 500/' /etc/php/8.2/fpm/pool.d/www.conf

# ---------------------------------------------------------
# 4. Install Composer
# ---------------------------------------------------------
RUN curl -sS https://mirrors.aliyun.com/composer/composer.phar -o /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# ---------------------------------------------------------
# 5. Set working directory and copy application files
# ---------------------------------------------------------
ENV DEPLOY_DIR="/var/www/dev_app_backend"
WORKDIR $DEPLOY_DIR

COPY . $DEPLOY_DIR
COPY .env /app/.env

# ---------------------------------------------------------
# 6. Install Laravel dependencies via Composer
# ---------------------------------------------------------
#ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-interaction --prefer-dist --no-progress
#RUN php -d memory_limit=-1 /usr/bin/composer install --no-interaction --no-progress --prefer-dist
#RUN composer install --no-interaction --prefer-dist --no-progress --no-plugins --no-scripts --prefer-stable
# ---------------------------------------------------------
# 7. Set folder permissions for Laravel
# ---------------------------------------------------------
RUN chown -R www-data:www-data $DEPLOY_DIR \
    && chmod -R 775 $DEPLOY_DIR/storage $DEPLOY_DIR/bootstrap/cache

# ---------------------------------------------------------
# 8. Generate cached Laravel config file (config.php)
# ---------------------------------------------------------
RUN php artisan config:cache \
    && php artisan route:cache

# ---------------------------------------------------------
# 9. Configure Nginx (assumes you have a custom site conf)
# ---------------------------------------------------------
COPY ngnix_backend.conf /etc/nginx/sites-available/dev_app_backend
RUN ln -s /etc/nginx/sites-available/dev_app_backend /etc/nginx/sites-enabled/ \
    && rm -f /etc/nginx/sites-enabled/default

# ---------------------------------------------------------
# 10. Expose port 1155 and start Nginx + PHP-FPM
# ---------------------------------------------------------
EXPOSE 1155

CMD service php8.2-fpm start && nginx -g 'daemon off;'
