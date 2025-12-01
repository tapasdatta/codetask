FROM php:8.4-cli

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json composer.lock* ./

# Install PHP dependencies
# Use ARG to allow different install modes
ARG INSTALL_DEV=false
RUN if [ "$INSTALL_DEV" = "true" ]; then \
        composer install --optimize-autoloader; \
    else \
        composer install --no-dev --optimize-autoloader; \
    fi

# Copy application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data /app
USER www-data

# Expose port (if needed for future web interface)
EXPOSE 8000

# Default command
CMD ["php", "index.php"]