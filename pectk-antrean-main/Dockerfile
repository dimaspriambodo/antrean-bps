FROM php:8.1-apache

# 1. Menggabungkan baris instalasi dengan tanda \ yang benar
# Catatan: libsound2 diubah menjadi libasound2 karena itu nama paket yang benar di Linux
RUN apt-get update && apt-get install -y \
    libnss3 libatk-bridge2.0-0 libxcomposite1 \
    libxdamage1 libxrandr2 \
    libgbm1 libasound2 libpangocairo-1.0-0 libgtk-3-0 \
    curl gnupg git unzip libicu-dev && \
    docker-php-ext-install intl mysqli pdo_mysql

# 2. Menginstal Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

WORKDIR /var/www/html
COPY . .

# 3. Memperbaiki perintah instalasi Composer (menghapus teks NPM dan spasi pada php)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev
RUN npm install

# 4. Mengatur folder public untuk CodeIgniter 4
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# 5. Menjalankan Apache (PHP) dan Node.js (Websocket) secara bersamaan
EXPOSE 8080
CMD node websocket.js & apache2-foreground