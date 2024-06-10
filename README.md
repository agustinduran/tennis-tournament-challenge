# Tennis Tournament Challenge 🎾

## Overview
This is a challenge backend project for GeoPagos company.

## Author

Agustín Durán

- GitHub: https://github.com/agustinduran
- LinkedIn: https://www.linkedin.com/in/agustineduran/

## Table of contents

- [Modelado de Datos](#modelado-de-datos)
- [Technology](#technology)
- [Pre Requisites](#pre-requisites)
- [Architecture](#architecture)
- [How To Install](#how-to-install)
- [Run](#run)
- [Routes](#routes)

## Modelado de Datos

![Diagrama de Entidad-Relación](docs/der.svg)

## Justificaciones de Diseño

## Technology

* Programming languange: PHP 8.1.19
* App Framework: Symfony 6.4.*
* Database engine: MariaDB

## Pre requisites

* Symfony 6.* with PHP 7.4.*
* Composer installed
* Linux/Mac terminal (Or emulated linux on Windows)
* No services running on localhost port 8000 or 3306

## Architecture
```scala
src/
├── Application/
│   ├── Command/
│   ├── Query/
│   ├── Service/
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
    │   ├── GenderRepository.php
    │   ├── TournamentRepository.php
    │   ├── PlayerRepository.php
    │   ├── PropertyRepository.php
    │   ├── PlayerPropertyValueRepository.php
    │   └── GameRepository.php
    ├── Controller/
    │   ├── TournamentController.php
    │   └── PlayerController.php
    └── Service/

```

## How to install

### Create a new database
```sql
CREATE DATABASE tennis_tournament_challenge;
```

### Set enviroment variables
#### Copy .env.example file
```
cp .env .env.local
```

#### Put your database host on
```
DB_HOST=[your_host]
```

#### Put your database port on
```
DB_PORT=[your_port]
```

#### Put your database username on
```
DB_USERNAME=[your_username]
```

#### Put your database password on
```
DB_PASSWORD=[your_password]
```

### Install dependencies
```
composer install
```

### Run migrations
```
php bin/console doctrine:migrations:migrate
```

### Run seeders
```
php bin/console app:seed-database
```

## API

## Run

### Run app
```
Symfony serve
```

## Routes

### 
```
http://localhost:8000
```