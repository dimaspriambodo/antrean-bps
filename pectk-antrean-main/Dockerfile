FROM php:8.1-apache

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    libnss3 libatk-bridge2.0-0 libxcomposite1 \
    libxdamage1 libxrandr2 \
    libgbm1 libasound2 libpangocairo-1.0-0 libgtk-3-0 \
    curl gnupg git unzip libicu-dev && \
    docker-php-ext-install intl mysqli pdo_mysql

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

WORKDIR /var/www/html
COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev
RUN npm install

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# --- TAMBAHKAN PERIZINAN FOLDER WRITABLE ---
# Memberikan izin akses tulis ke folder writable agar cache bisa jalan
RUN chown -R www-data:www-data /var/www/html/writable && chmod -R 777 /var/www/html/writable
# -------------------------------------------

EXPOSE 8080

CMD /bin/bash -c "rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* ; node websocket.js & apache2-foreground"