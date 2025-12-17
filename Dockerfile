# Usamos la imagen oficial de PHP con Apache
FROM php:8.4-apache

# Instalar extensiones necesarias y herramientas
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && a2enmod rewrite \
    && apt-get clean

# Copiar tu proyecto al contenedor
# Suponiendo que tu proyecto está en el mismo directorio que el Dockerfile
COPY ./ /var/www/html/

# Establecer permisos (opcional)
RUN chown -R www-data:www-data /var/www/html

# Puerto
EXPOSE 80
