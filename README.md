# PHP Project with Docker and Phinx

## Table of Contents
- [Introduction](#introduction)
- [To-Do List](#to-do-list)
- [Prerequisites](#prerequisites)
- [Setup](#setup)
- [Running the Application](#running-the-application)
- [Database Migrations](#database-migrations)
- [Running Tests](#running-tests)
- [Seeding the Database](#seeding-the-database)
- [Configuration](#configuration)
- [Usage](#usage)
- [License](#license)

## Introduction
API layer with the following main requirements - API which allows users to add, edit, delete and retrieve customers - A BusinessLogic layer where any business rules and logic will live - A DataTransferObjects layer where any DTOs are stored - A repository layer where entities are saved

## To-Do List
- [ ] Complete the database schema
- [ ] Implement additional features
- [ ] Write unit tests
- [ ] Update documentation

## Prerequisites
Before you begin, ensure you have met the following requirements:
- [Docker](https://docs.docker.com/get-docker/) installed on your machine.
- [Docker Compose](https://docs.docker.com/compose/install/) installed on your machine.

## Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/jovejob/php.git
   cd php

2. Build and start the Docker containers:
   ```bash
   docker-compose up -d

3.Access the PHP container:
   ```bash
   docker exec -it php_app bash
   ```

4.Install dependencies: Inside the container, run:
   ```bash
   composer install
   ```

5.Set up your database.
Ensure your database is set up correctly in the phinx.yml file and Docker is properly configured to use MySQL.

## Running the Application

1. Once your containers are up and running, you can access your application in your web browser at:
http://localhost:8080/

## Database Migrations
Running Migrations
To run the database migrations using Phinx, execute the following command inside the PHP container:
```bash
docker exec -it php_app vendor/bin/phinx migrate
```

## Seeding the Database
To populate your database with initial data, run the following command inside the PHP container:
```bash
docker exec -it php_app vendor/bin/phinx seed:run
```

## Running Tests
To run your tests, execute the following command inside the PHP container:
```bash
docker exec -it php_app ./vendor/bin/phpunit tests
```


## Configuration
Ensure your database connection settings are configured in the phinx.yml file. It should look something like this:
paths:
    migrations: db/migrations
    seeds: db/seeds

environments:
  default_migration_table: phinxlog
  default_environment: development
  development:
    adapter: mysql
    host: db   # or '127.0.0.1'
    #    host: localhost   # or '127.0.0.1'
    name: customer_db
    user: user
    pass: password
    #    port: 3307
    port: 3306
    charset: utf8

## Usage
- TODO

## License
This project is licensed under the MIT License
