# Aplicación PHP basada en Domain-Driven Development (DDD)

Esta es una aplicación PHP que sigue los principios de **Domain-Driven Development (DDD)** y utiliza
el patrón arquitectónico de **Puertos y Adaptadores** (también conocido como Hexagonal Architecture)
. Además, las pruebas están implementadas con **PHPUnit**.

## Características principales

- **Domain-Driven Development (DDD):** La lógica de negocio está organizada en torno a un modelo de
  dominio bien definido.
- **Patrón de Puertos y Adaptadores:** Separa la lógica de negocio del resto de la infraestructura,
  facilitando la escalabilidad y el mantenimiento.
- **Pruebas con PHPUnit:** Asegura la calidad del código mediante pruebas unitarias y de
  integración.

## Requisitos

- PHP 8.0 o superior
- Composer Docker (opcional, para ejecutar en contenedores)
- PHPUnit (para pruebas)

---

## Entorno Local

### Instalación

- Clona este repositorio:
  ```bash
    git clone <URL_DEL_REPOSITORIO>
    cd <NOMBRE_DEL_REPOSITORIO>
  ```
- Instala las dependencias con Composer:
  ```bash
  composer install
  ```

### Administrar la base de datos con Doctrine

Para gestionar la base de datos, se utiliza **Doctrine ORM**. A continuación, se presentan los
comandos principales:

- Crear el esquema de la base de datos:
  ```bash
  php doctrine.php orm:schema-tool:create
  ```
- Eliminar el esquema de la base de datos:
  ```bash
  php doctrine.php orm:schema-tool:drop --force
  ```

### Pruebas con PHPUnit

- Ejecuta las pruebas unitarias utilizando PHPUnit con el siguiente comando:
  ```bash
  php vendor/bin/phpunit
  ```

### Arrancar servidor

- Se recomienda usar el servidor web interno de PHP:
  ```bash
  cd public
  php -S localhost:80
  ```

## Uso de Docker

## Instalación

- Clona este repositorio:
  ```bash
    git clone <URL_DEL_REPOSITORIO>
    cd <NOMBRE_DEL_REPOSITORIO>
  ```
- La aplicación puede ejecutarse en un entorno Docker. Para iniciar los contenedores, utiliza:
  ```bash
  docker-compose build
  docker-compose up -d
  ```
- Pruebas unitarias:
  ```bash
  docker exec -it php-app php vendor/bin/phpunit
  ```
- Crear el esquema de la base de datos:
  ```bash
  docker exec -it php-app php /var/www/doctrine.php orm:schema-tool:create
  ```
- Borrar el esquema de la base de datos:
  ```bash
  docker exec -it php-app php /var/www/doctrine.php orm:schema-tool:drop --force
  ```

## Ejemplos de uso con Curl

A continuación, se presentan ejemplos de cómo interactuar con la API utilizando curl:

- Eliminar usuario:
  ```bash
  curl -X DELETE http://localhost:80/delete?id=1 -H "Content-Type: application/json"
  ```
- Comprobar el estado del servidor:
  ```bash
  curl -X GET http://localhost:80/ping -H "Content-Type: application/json"
  ```
- Registrar usuario:
  ```bash
  curl -X POST http://localhost:80/register -H "Content-Type: application/json" -d "{\"name\": \"John Doe\", \"email\": \"johndoe@example.com\", \"password\": \"Mexico.2025\"}"
  ```
- Obtener un usuario por ID:
  ```bash
  curl -X GET "http://localhost:80/user?id=1" -H "Content-Type: application/json"
  ```
- Obtener un usuario por Email:
  ```bash
  curl -X GET "http://localhost:80/user?email=johndoe@example.com" -H "Content-Type: application/json"
  ```
- Listar todos los usuarios:
  ```bash
  curl -X GET http://localhost:80/users -H "Content-Type: application/json"
  ```
