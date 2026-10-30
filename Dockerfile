
# =========================
# 1. Imagen base
# =========================
FROM php:7.4-fpm

# Configuración general
ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1

# =========================
# 2. Dependencias del sistema
# =========================
RUN set -eux; \
    sed -i 's|deb.debian.org/debian|archive.debian.org/debian|g' /etc/apt/sources.list; \
    sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list; \
    echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git \
        curl \
        unzip \
        zip \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libpq-dev; \
    rm -rf /var/lib/apt/lists/*

# =========================
# 3. Extensiones PHP
# =========================
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        mbstring \
        zip \
        gd \
        exif \
        bcmath

# =========================
# 4. Instalar Composer
# =========================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================
# 5. Directorio de trabajo
# =========================
WORKDIR /var/www

# =========================
# 6. Configuración PHP
# =========================
COPY php.ini /usr/local/etc/php/conf.d/uploads.ini

# =========================
# 7. Copiar proyecto
# =========================
COPY . .

# =========================
# 8. Instalar dependencias Laravel
# =========================
RUN composer install --no-dev --optimize-autoloader

# =========================
# 9. Permisos Laravel
# =========================
RUN chmod -R 775 storage bootstrap/cache

# =========================
# 10. Limpiar caché
# =========================
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

# =========================
# 11. Puerto
# =========================
EXPOSE 8000

# =========================
# 12. Iniciar aplicación
# =========================
CMD php artisan optimize:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
