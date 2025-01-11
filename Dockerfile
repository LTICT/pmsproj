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
       nginx \
       git \
       curl \
       zip \
       unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer from Aliyun mirror (like your Jenkinsfile does)
RUN curl -sS https://mirrors.aliyun.com/composer/composer.phar -o /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# ---------------------------------------------------------
# 2. Set working directory and copy application files
# ---------------------------------------------------------
ENV DEPLOY_DIR="/var/www/dev_app_backend"
WORKDIR $DEPLOY_DIR

# Copy your application code into the container
# (Assumes your Dockerfile is at the root of your project)
COPY . $DEPLOY_DIR

#COPY .env /app/.env

# (Optional) If you store .env somewhere else, or only want to copy it from your host:
# COPY path/to/local/env/backend/.env $DEPLOY_DIR/.env

# ---------------------------------------------------------
# 3. Install Laravel dependencies via Composer
# ---------------------------------------------------------
RUN composer install --no-interaction --prefer-dist --no-progress

# ---------------------------------------------------------
# 4. Set folder permissions for Laravel
# ---------------------------------------------------------
RUN chown -R www-data:www-data $DEPLOY_DIR \
    && chmod -R 775 $DEPLOY_DIR/storage $DEPLOY_DIR/bootstrap/cache

# ---------------------------------------------------------
# 5. Configure Nginx (assumes you have a custom site conf)
# ---------------------------------------------------------
# Example: you have a dev_app_backend.conf that listens on port 1155
COPY ngnix_backend.conf /etc/nginx/sites-available/dev_app_backend
RUN ln -s /etc/nginx/sites-available/dev_app_backend /etc/nginx/sites-enabled/ \
    && rm -f /etc/nginx/sites-enabled/default


# ---------------------------------------------------------
# 6. Expose port 1155 and start Nginx + PHP-FPM
# ---------------------------------------------------------
EXPOSE 1155

# Start both processes in the foreground so Docker can track them.
CMD service php8.2-fpm start && nginx -g 'daemon off;'
