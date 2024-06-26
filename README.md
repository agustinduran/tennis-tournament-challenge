# Tennis Tournament Challenge 🎾

## Descripción 📄
Este es un proyecto challenge backend realizado para GeoPagos.

Si querés probar la API de manera rápida, accedé a este link:
[Documentación de la API](https://52jf9vqk-8000.brs.devtunnels.ms/api/doc) 🌐

URL Limpia: [https://52jf9vqk-8000.brs.devtunnels.ms/api/doc](https://52jf9vqk-8000.brs.devtunnels.ms/api/doc)

## Autor 👨‍💻

Agustín Durán

- [GitHub](https://github.com/agustinduran)
- [LinkedIn](https://www.linkedin.com/in/agustineduran/)

## Tabla de Contenidos 📑

- [Diseño de Sistema](#diseño-de-sistema)
- [Pruebas Funcionales Manuales](#pruebas-funcionales-manuales)
- [Tecnología](#tecnología)
- [Pre Requisitos](#pre-requisitos)
- [Patrón de Arquitectura](#patrón-de-arquitectura)
- [Instrucciones](#cómo-instalar)
- [Ejecutar](#ejecutar)
- [Pruebas Automatizadas](#pruebas-automatizadas)
- [Despliegue](#despliegue)
- [Rutas](#rutas)

## Diseño de Sistema 🛠️

### Modelado de Datos

![Diagrama de Entidad-Relación](docs/der.svg)

### Justificaciones de Diseño

Para resolver este desafío, opté por desacoplar los valores de las propiedades (`player_property_values`) de la definición de las propiedades (`player_properties`). Las propiedades como Fuerza, Tiempo de Reacción, etc., se definirán en la tabla `player_properties`, mientras que los valores específicos de cada jugador se almacenarán en `player_property_values`. Esto proporciona una mayor extensibilidad y flexibilidad en la gestión de las propiedades de los jugadores.

El torneo está compuesto por partidos (`games`), cada uno con una etapa definida (`stage`). La etapa se numera de manera que 1 representa la final, 2 semifinales, 3 cuartos de final, y así sucesivamente. Estos partidos están vinculados entre sí mediante un enlace al siguiente partido en el cuadro, lo que permite determinar quién jugará contra quién en los encuentros siguientes. Una vez que se determina el ganador de un partido, se almacena en el atributo `player_winner_id`.

Para obtener el ganador del torneo, simplemente se debe realizar una intersección entre la tabla `games` y `tournament`, filtrando por `stage` 1 y obteniendo el `player_winner_id`.

### Algoritmo para Generación de Partidos

El algoritmo para la generación de partidos en un torneo con un número de jugadores igual a una potencia de 2 (por ejemplo, 2, 4, 8, 16, etc.) funciona de la siguiente manera:

1. **Validación de Jugadores**:
    - Verificar que el número de jugadores sea una potencia de 2 y al menos 2 jugadores.
    - Si la validación falla, lanzar una excepción.

2. **Inicialización**:
    - Crear una lista de partidas vacías.
    - Asignar el total de jugadores y calcular el número inicial de etapas (`stages`).

3. **Creación de Partidos**:
    - Para cada etapa, crear partidos de manera aleatoria entre los jugadores disponibles.
    - En la primera etapa, los partidos se crean directamente con los jugadores proporcionados.
    - Para etapas siguientes, los ganadores de los partidos de la etapa anterior se emparejan aleatoriamente.
    - Asignar a cada partido creado su siguiente partido en la etapa correspondiente.

4. **Persistencia de Datos**:
    - Guardar cada partido creado en la base de datos.

### Algoritmo Determinador de Ganador en un Partido

El algoritmo para determinar el ganador de un partido se basa en tres componentes principales:

![Fórmula](docs/formula-white.png)

1. **Nivel de Habilidad (habilityLevel) y Suerte del Jugador (luckyLevel)**:
    - Se consideran los valores de `habilityLevel` y `luckyLevel` de cada jugador.
    - Estos valores tienen la mayor preponderancia en el cálculo del puntaje (Un 80% del puntaje total).

2. **Valores de Propiedades del Jugador**:
    - Se suman los valores de `PlayerPropertyValues` de cada jugador.
    - Estos valores tienen una preponderancia secundaria en el cálculo del puntaje (Un 20% del puntaje total).

3. **Factor Aleatorio**:
    - Se añade un pequeño valor aleatorio para evitar empates entre jugadores con los mismos stats.
    - Este valor tiene la menor preponderancia en el cálculo del puntaje (Un 5% del puntaje total).

El puntaje total de cada jugador se calcula sumando estos componentes. Si los puntajes son iguales, se llama recursivamente a este mismo método hasta obtener un ganador.

## Pruebas Funcionales Manuales 🧪

#### Generar un Torneo y Obtener el Ganador del Mismo
1. **Crear un Torneo Nuevo**
   - Realiza una solicitud `POST` a `/api/tournaments` desde la documentación de la API para crear un torneo nuevo.
   - Guarda el `id` resultante del torneo creado.

2. **Simular Completo el Torneo**
   - Inserta el `id` del torneo en la solicitud `POST` a `/api/tournaments/{id}/simulate-complete`.
   - Proporciona la data necesaria en el body (utiliza los datos de ejemplo si es un torneo masculino, para torneos femeninos deberás crear jugadores femeninos).
   
#### Generar un Torneo y Obtener el Ganador por Etapas
1. **Crear un Torneo Nuevo**
   - Realiza una solicitud `POST` a `/api/tournaments` desde la documentación de la API para crear un torneo nuevo.
   - Guarda el `id` resultante del torneo creado.

2. **Generar Juegos**
   - Inserta el `id` del torneo en la solicitud `POST` a `/api/tournaments/{id}/generate-games`.
   - Proporciona en el body los `ids` de los jugadores.

3. **Simular Partidos**
   - Realiza una solicitud `POST` a `/api/tournaments/{id}/simulate` para simular los partidos.
   - Obtén el ganador y el bracket final.

#### Obtener un Torneo y el Ganador del Mismo
1. **Buscar Torneo por ID**
   - Inserta el `id` del torneo en la solicitud `GET` a `/api/tournaments/{id}/winner` para obtener el ganador del torneo.

#### Obtener un Torneo con Filtro de Fecha y Género
1. **Búsqueda Avanzada de Torneos**
   - Realiza una solicitud `GET` a `/api/tournaments` utilizando los query params para filtrar por fecha y género.
   - Para obtener todos los torneos, no utilices ningún parámetro de búsqueda.

## Tecnología 🖥️

* Lenguaje de programación: PHP 8.1.19
* Framework: Symfony 6.4.*
* Motor de base de datos: MariaDB

## Pre Requisitos 📋

* Symfony 6.* con PHP 8.1.*
* Composer instalado
* Terminal Linux/Mac (o emulación de Linux en Windows)
* No tener servicios corriendo en `localhost` puertos 8000 o 3306

## Patrón de Arquitectura 🏛️

Se ha implementado una arquitectura hexagonal con algunos principios del DDD.

```scala
src/
├── Application/
│   ├── Command/
│   ├── Query/
│   ├── Service/
│   │   ├── CreatePlayerService.php
│   │   ├── RandomizeTournamentResultService.php
│   │   └── [...]
│   └── DTO/
├── Domain/
│   ├── Model/
│   │   ├── Gender.php
│   │   ├── Tournament.php
│   │   ├── Player.php
│   │   ├── Property.php
│   │   ├── PlayerPropertyValue.php
│   │   └── Game.php
│   ├── Repository/
│   │   ├── GenderRepository.php
│   │   ├── TournamentRepository.php
│   │   ├── PlayerRepository.php
│   │   ├── PropertyRepository.php
│   │   ├── PlayerPropertyValueRepository.php
│   │   └── GameRepository.php
│   ├── Service/
│   └── ValueObject/
└── Infrastructure/
    ├── Doctrine/
    │   ├── DoctrineGenderRepository.php
    │   ├── DoctrineTournamentRepository.php
    │   ├── DoctrinePlayerRepository.php
    │   ├── DoctrinePropertyRepository.php
    │   ├── DoctrinePlayerPropertyValueRepository.php
    │   └── DoctrineGameRepository.php
    ├── Controller/
    │   ├── PlayerController.php
    │   ├── TournamentController.php
    │   └── [...]
    └── Service/
```

## Cómo Instalar ⚙️

### Crear base de datos
```sql
CREATE DATABASE tennis_tournament_challenge;
```

### Insertar variables de entorno
#### Copiar archivo .env
```
cp .env .env.local
```

#### Insertar las credenciales de tu servidor web en el .env
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/tennis_tournament_challenge"
```

### Instalar dependencias
```
composer install
```

### Ejecutar migrations
```
php bin/console doctrine:migrations:migrate
```

### Ejecutar seeders
```
php bin/console app:seed-database
```

## Ejecutar 🚀

### Ejecutar App
```
Symfony serve
```

## Pruebas Automatizadas 🧪

### Crear base de datos para pruebas
```sql
CREATE DATABASE tennis_tournament_challenge_test;
```


### Copiar archivo .env.test
```
cp .env.test .env.test.local
```

#### Insertá las credenciales de tu base de datos de pruebas en el .env.test
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/tennis_tournament_challenge_test"
```

### Ejecutar pruebas de integración y unitarias
```
php bin/phpunit
```

## Rutas 🌍

### API Documentation
```
http://localhost:8000/api/doc
```

## Despliegue 📦

Se puede acceder a la API mediante esta dirección
```
https://52jf9vqk-8000.brs.devtunnels.ms/api/doc
```