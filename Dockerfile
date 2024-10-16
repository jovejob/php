FROM php:8.1-cli

# Install PDO and MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install

CMD ["php", "index.php"]
