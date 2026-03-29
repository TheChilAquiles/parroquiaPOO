FROM php:8.2-apache

# Instalar extensiones de PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite para el .htaccess del MVC
RUN a2enmod rewrite

# Copiar el código del proyecto al servidor
COPY . /var/www/html/

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html