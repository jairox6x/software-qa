# Tarea 3 Calidad software
Aplicación de pruebas para demostrar algunos conceptos de la materia de calidad de software de la UAPA

## Inicialización
Para correr el proyecto se deben seguir las instrucciones a continuación : 

### Prerequisitos


* Git.
* PHP.
* Composer.
* Laravel CLI.
* A webserver like Nginx or Apache.


### Instalación
Clone el repo git en su PC

```$ git clone https://github.com/jairox6x/software-qa.git```


Tambien puede descargar el repositorio como un archivo zip

Despues de clonar el repositorio debe descargar las dependencias 

```
$ cd nombre-proyecto
$ composer install
```


### Setup
- Cuando finalice la instalación, copie el archivo `.env.example` a `.env`

  ```$ cp .env.example .env```


- Genere el application key

  ```$ php artisan key:generate```


- Agregue las credenciales necesarias para la base de datos en el archivo `env`

- Migrar la aplicación

  ```$ php artisan migrate```

- Instalar laravel passport

  ```$ php artisan passport:install```

- Llenar la Database con datos de pruebas

  ```$ php artisan db:seed```


- Crear una base de datos Sqlite 

  ```$ touch database/test.sqlite```

- Copiar el archivo `.env.testing.example` a `.env.testing`

  ```$ cp .env.testing.example .env.testing```

- Migrar la database de pruebas

  ```$ php artisan migrate --seed --env=testing```



### Correr la aplicación

  ```$ php artisan serve```

### Correr las pruebas funcionales 

  ```$ ./vendor/bin/phpunit```


## Creado con :
* [Laravel](https://laravel.com) - 

