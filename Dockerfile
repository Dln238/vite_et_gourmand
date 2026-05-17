FROM php:8.2-apache

# Installation des dépendances requises
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    libssl-dev \
    && docker-php-ext-install pdo_mysql

# Installation de l'extension MongoDB pour PHP
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Activation du module de réécriture d'URL d'Apache (nécessaire pour le futur MVC)
RUN a2enmod rewrite
