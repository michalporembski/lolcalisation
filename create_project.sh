#!/bin/sh
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/bin --filename=composer
php -r "unlink('composer-setup.php');"
composer create-project symfony/website-skeleton lolcalisation
cd lolcalisation/
composer require server --dev
composer require --dev profiler
composer require twig
composer require translator
composer require doctrine maker
php bin/console doctrine:database:create
php bin/console make:entity content
php bin/console make:controller ContentController
composer require annotations
composer require friendsofsymfony/user-bundle "~2.0"
php bin/console make:entity user
php bin/console make:controller LanguageController
php bin/console server:run
