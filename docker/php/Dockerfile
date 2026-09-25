FROM php:8.3-fpm

# Install runtime system libraries, build tools, and PHP extensions via apt-get
RUN set -xe; \
    # Update package lists and install system dependencies
    apt-get update && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        less \
        mariadb-client \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        libicu-dev \
        libmagickwand-dev \
    && \
    update-ca-certificates \
    && \
    # Configure GD extension with FreeType and JPEG support
    docker-php-ext-configure gd --with-freetype --with-jpeg \
    && \
    # Install core PHP extensions required by WordPress and WooCommerce
    docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
        gd \
        opcache \
        zip \
        bcmath \
        intl \
        exif \
    && \
    # Install third-party extensions via PECL
    pecl install redis imagick \
    && \
    # Enable PECL extensions
    docker-php-ext-enable redis imagick \
    && \
    # Install WP-CLI
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp \
    && \
    # Clean up apt cache to keep image size small
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/pear