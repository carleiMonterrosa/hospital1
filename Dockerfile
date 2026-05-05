# Usamos una imagen de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones de sistema necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    zip \
    libicu-dev \
    unzip \
    git \
    curl

# Instalar extensiones de PHP para Laravel y SQL
RUN docker-php-ext-install pdo_mysql gd intl

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar el proyecto
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Dar permisos a storage y bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Cambiar el DocumentRoot de Apache a la carpeta public de Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80