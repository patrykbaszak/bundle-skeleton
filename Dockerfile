FROM php:latest

RUN apt update \
    && apt-get update \
    && apt install -y git unzip

# install composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
