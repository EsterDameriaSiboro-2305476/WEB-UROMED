FROM php:8.2-cli

# Install dependencies untuk PHP
RUN apt-get update && apt-get install -y \
    git unzip curl gnupg lsb-release ca-certificates \
    libicu-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo_mysql mbstring xml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Install Node.js 20.x LTS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs


# Set workdir
WORKDIR /app

# Expose port PHP dev server
EXPOSE 8000

# Default command: jalankan PHP built-in server (untuk Laravel)
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
