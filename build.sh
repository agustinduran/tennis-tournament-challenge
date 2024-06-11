#!/bin/bash

# Instalar dependencias de Composer
composer install --no-dev --optimize-autoloader --no-interaction

# Crear los directorios
mkdir -p public

# Copiar los archivos
cp -R vendor public/
cp -R config public/
cp -R src public/
cp -R templates public/
cp -R .env public/

# Construir la aplicación
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug
