FROM php:8.3-apache as basephp83
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions &&  \
    install-php-extensions gd xdebug

# extensions
RUN install-php-extensions ctype  \
    && install-php-extensions curl \
    && install-php-extensions json \
    && install-php-extensions pdo \
    && install-php-extensions zip \
    && install-php-extensions pdo_pgsql \
    && install-php-extensions intl \
    && install-php-extensions opcache

RUN apt update \
    && apt upgrade -y \
    && apt install -y nano \
    && apt install -y unzip

# rewrite_mod for htaccess
RUN a2enmod rewrite
RUN a2enmod headers

# mod configuration
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer