FROM php:7.2-fpm

RUN apt-get update && apt-get install -y libpq-dev git unzip zlib1g-dev gnupg libicu-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_pgsql zip intl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#RUN curl -L https://www.npmjs.com/install.sh | sh
#RUN npm install -g yarn

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash - && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && \
    apt-get install -yq -y build-essential nodejs yarn

RUN pecl install xdebug
RUN docker-php-ext-enable --ini-name xdebug.ini xdebug
RUN { \
    echo 'xdebug.remote_enable=On'; \
    echo 'xdebug.remote_autostart=Off'; \
} >> /usr/local/etc/php/conf.d/xdebug.ini