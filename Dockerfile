FROM php:8.2-apache

# 1. Instalar dependencias del sistema necesarias para Composer y MySQL
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip

# 2. Instalar Composer de forma oficial
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 3. Habilitar el módulo de reescritura para el MVC (.htaccess)
RUN a2enmod rewrite

# 4. Copiar los archivos del proyecto
COPY . /var/www/html/

# 5. Forzar la instalación de dependencias
# Esto creará la carpeta /var/www/html/vendor/
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 6. Permisos para Apache
RUN chown -R www-data:www-data /var/www/html