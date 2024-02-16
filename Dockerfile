FROM php:7.4-fpm


# Install necessary dependencies
RUN apt-get update \
    && apt-get install -y \
        # apache2 \
        curl \
        wget \
        git \
        zip
        # software-properties-common

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer
