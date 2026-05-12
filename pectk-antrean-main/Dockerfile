FROM php:8.1-apache

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

# --- INI OBAT UNTUK ERROR MPM CRASH ---
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
# --------------------------------------

EXPOSE 8080
CMD node websocket.js & apache2-foreground

RUN rm -f /etc/apache2/mods-enabled/mpm_*.load && a2enmod mpm_prefork