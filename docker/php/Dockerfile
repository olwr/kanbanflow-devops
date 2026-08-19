FROM php:8.4-apache

LABEL org.opencontainers.image.title="KanbanFlow App" \
      org.opencontainers.image.description="Apache 2.4 + PHP 8.4 servindo o quadro Kanban" \
      org.opencontainers.image.licenses="MIT"

# Extensões PHP necessárias para comunicação com MariaDB via PDO
RUN apt-get update && apt-get install -y --no-install-recommends \
        curl \
        default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite headers \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configuração do servidor
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-kanbanflow.ini

# Código da aplicação (em desenvolvimento é sobreposto por bind mount)
COPY src/ /var/www/html
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html
EXPOSE 81

HEALTHCHECK --interval=15s --timeout=5s --start-period=20s --retries=5 \
    CMD curl -fsS http://localhost/api/health.php || exit 1