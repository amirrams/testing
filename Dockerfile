FROM php:8.2-apache

# Actualizar los repositorios
RUN apt update

# Instalar dependencias necesarias
RUN apt install -y unzip curl git

# Limpiar el caché de apt
RUN apt clean
RUN rm -rf /var/lib/apt/lists/*

# Instalar drivers para MySQL
RUN docker-php-ext-install pdo_mysql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Establecer el directorio de trabajo
WORKDIR /var/www/

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

