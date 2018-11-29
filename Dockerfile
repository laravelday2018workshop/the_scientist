# This docker image is made only for the workshop
# Please consider optimizing it:
# - using php:fpm + nginx
# - removing unecessary composer dependency
# - using an entrypoint
FROM composer:1.7.3 as composer

FROM php:7.1

# Define ENVs and ARGs
ARG XDEBUG_REMOTE_HOST=docker.for.mac.localhost
ENV XDEBUG_CONFIGURATION_FILE='/usr/local/etc/php/conf.d/xdebug.ini'

# Copy the codebase in the main direcotry
WORKDIR /app
COPY ./ /app

# Install dependeies for PHP
RUN apt-get update && \
    apt-get install -y git zlib1g-dev && \
    docker-php-ext-install zip pdo pdo_mysql

# Install dependecies (dev included) and run framework commands
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install
RUN mv .env.example .env
RUN php artisan key:generate

# Install XDebug and add XDebug configurations
RUN yes | pecl install xdebug
RUN echo 'xdebug.idekey=SCIENCE' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.remote_enable=1' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.remote_port=9090' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.remote_connect_back=0' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.remote_autostart=1' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.remote_log="/var/log/xdebug/xdebug.log"' >> $XDEBUG_CONFIGURATION_FILE && \
    echo "xdebug.remote_host=$XDEBUG_REMOTE_HOST" >> $XDEBUG_CONFIGURATION_FILE && \
    echo ';;settings for profiling' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.profiler_enable_trigger=1' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.profiler_output_name=xdebug.out.%t' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.profiler_output_dir="/tmp/xdebug"' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.profiler_enable_trigger=1' >> $XDEBUG_CONFIGURATION_FILE && \
    echo 'xdebug.trace_enable_trigger=1' >> $XDEBUG_CONFIGURATION_FILE && \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" >> $XDEBUG_CONFIGURATION_FILE

EXPOSE 8000
CMD php artisan migrate && php artisan serve --host=0.0.0.0
