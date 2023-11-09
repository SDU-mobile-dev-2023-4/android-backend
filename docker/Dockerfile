FROM php:8.2.8-apache

# Apache configuration
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
WORKDIR /var/www/html

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
	&& sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
	&& a2enmod rewrite

# Use the default php production configuration and set memory to 1024MB
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
	&& sed -i 's/memory_limit = .*/memory_limit = 1024M/' "$PHP_INI_DIR/php.ini"

# Apps
RUN apt-get -qq update && apt-get install -y \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
	zlib1g-dev libicu-dev g++\
	zip libzip-dev git; \
	rm -r /var/lib/apt/lists/*;

# PHP Extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg > /dev/null  \
	&& docker-php-ext-install -j$(nproc) gd > /dev/null \
	&& docker-php-ext-configure intl > /dev/null \
	&& docker-php-ext-install intl > /dev/null \
	&& docker-php-ext-install zip > /dev/null \
	&& docker-php-ext-install pcntl > /dev/null \
	&& docker-php-ext-install mysqli pdo_mysql bcmath ctype > /dev/null

RUN echo "\nServerName 127.0.0.1" >> /etc/apache2/apache2.conf; \
	echo "\nServerSignature Off\nServerTokens Prod\nFileETag None" >> /etc/apache2/apache2.conf

# Insert data
COPY . /var/www/html

# Permission
RUN mkdir -p /var/www/html/bootstrap/cache; \ 
	chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache; \
	chmod g+s /var/www/html/storage /var/www/html/bootstrap/cache

# Remove storage link, then run storage link again
RUN rm -rf /var/www/html/public/storage; \
	php artisan storage:link

# Apache setup
RUN apt-get install libapache2-mod-security2 libapache2-mod-evasive git curl -y ;\
	mv /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf ;\
	sed -i "s/SecRuleEngine DetectionOnly/SecRuleEngine On/" /etc/modsecurity/modsecurity.conf ; \
	sed -i "s/SecResponseBodyAccess On/SecResponseBodyAccess Off/" /etc/modsecurity/modsecurity.conf \
	a2enmod headers ;\
	mkdir /var/log/mod_evasive ;\
	chown -R www-data:www-data /var/log/mod_evasive
