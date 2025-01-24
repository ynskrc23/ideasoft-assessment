# PHP ve Apache tabanlı bir imaj kullanıyoruz
FROM php:8.2-apache

# Çalışma dizinini ayarla
WORKDIR /var/www/html

# Gerekli PHP eklentilerini yükle
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Apache rewrite modülünü etkinleştir
RUN a2enmod rewrite

# Composer'ı yükle
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Proje dosyalarını konteynere kopyala
COPY . .

# Bağımlılıkları yükle
RUN composer install --no-dev --optimize-autoloader

# Apache'nin 80 portunu dinlemesini sağla
EXPOSE 80