# pandora-fms-test
Prueba técnica PandoraFMS

# Pasos para la instalación

* Instalar dependencias php: "composer install"
* Instalar dependencias node: "npm install"
* Configurar correctamente el fichero .env
  * conexión a MySQL
  * usuario y password de BBDD
  * APP_URL url donde se accedera a la aplicacion
* Correr migraciones: "php artisan migrate"
* Correr las dependencias de Node: "npm run dev"

## Instalacion
```shell
git clone https://github.com/juaniyyo/pandora-fms-test.git
cd pandora-fms-test
composer install
npm install
npm run dev
# Configurar fichero .env según su entorno
php artisan key:generate
php artisan migrate
```

****************************************************************

Se adjunta el fichero .env en el que se encuentran los
datos necesarios para el envío de correos desde la app.

Mas información sobre como desplegar una app de Laravel:
Laravel doc [Laravel](https://laravel.com/docs/10.x)

******************************************************************
También se puede comprobar el funcionamiento de la app en el siguiente enlace:

App prueba técnica [App Reservas Online](https://1024mbits.es)

******************************************************************
Repo:
[Pandora Test](https://github.com/juaniyyo/pandora-fms-test)
