# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Instalación del proyecto

* Ejecutar el comando `composer install`, esto instalará las dependencias del proyecto (debe contar con [composer](https://getcomposer.org/download/ "composer download") instalado en el sistema).
* (opcional) Generar un random string alfanúmerico de 32 catacteres [https://www.browserling.com/tools/random-string](https://www.browserling.com/tools/random-string "") y copiarla en el valor `APP_KEY` del archivo `.env` de la raíz.
* Configurar los accesos a la base de datos en el archivo `.env`
    ```
    DB_HOST=Host de la base de datos (ej: localhost 0 127.0.0.1)
    DB_PORT=Puerto (ej: 3306)
    DB_DATABASE=Nombre de la base de datos
    DB_USERNAME=usuario de la base de datos
    DB_PASSWORD=Contraseña de la base de datos
    ```
* Ejecutar la migración de la base de datos para crear las tablas y poblarlas se debe ejecutar el comando `php artisan migrate --seed`

## Iniciar el servidor

Para iniciar el servidor debes ejecutar el comando `php -S localhost:8000 -t public` desde la carpeta raíz del proyecto.

Para más información sobre el framework consultar la documentación oficial.
