##  Ejecute los siguientes pasos para ejecutar el ejemplo de manera local

1. Clone el repositorio
```
git clone  https://github.com/atilas88/laravel_passport_swagger.git
```
2. Dentro de la carpeta del proyecto:
```
composer install
```
3. Cree el archivo .env
```
cp .env.example .env
```
Dentro de .env configurar la base de datos y copiar L5_SWAGGER_GENERATE_ALWAYS = true
4. Genere la llave de la app
```
php artisan key:generate
```
5. Ejecute las migraciones
```
php artisan migrate
```
6. Genere las llaves de generar access_token, el token personal y de cliente
```
php artisan passport:install
```
El valor del **Client_ID** y el de **Client_secret** son los que se deben usar en la interfaz de documentación para autorizar el resto del api.
7. Inicie la app
```
php artisan serve
```
8. Consulte el api ejecutando:
http://ip_serv:puerto/api/documentation

Registrar uno o varios usuarios, autorizar el api protegida con **Client_ID** y el **Client_secret** obtenidos en el paso 6.

