# Dockerfile
FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite pour Apache (nécessaire pour le .htaccess)
RUN a2enmod rewrite

# Supprime le warning AH00558 au demarrage Apache dans le conteneur
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf && a2enconf servername

# Configurer Apache pour pointer sur le dossier public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|' /etc/apache2/sites-available/000-default.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier tous les fichiers du projet dans l'image
COPY . .

# Si vous utilisez Composer, installez les dépendances (si vous avez un autoloader)
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# RUN composer install --no-interaction --optimize-autoloader

# Créer les dossiers nécessaires s'ils n'existent pas (optionnel)
RUN mkdir -p /var/www/html/app/cache /var/www/html/app/log && \
    chown -R www-data:www-data /var/www/html/app/cache /var/www/html/app/log && \
    chmod -R 755 /var/www/html/app/cache /var/www/html/app/log

# Exposer le port 80
EXPOSE 80