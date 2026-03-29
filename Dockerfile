FROM php:8.2-apache

# 1. Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# 2. Instalar Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 3. Habilitar mod_rewrite para el .htaccess
RUN a2enmod rewrite

# 4. Copiar el código al servidor
COPY . /var/www/html/

# 5. Ejecutar composer install para generar la carpeta /vendor
# Usamos --no-dev para que sea más ligero en producción
RUN composer install --no-interaction --no-dev --optimize-autoloader

# 6. Ajustar permisos para que Apache pueda leer todo
RUN chown -R www-data:www-data /var/www/html