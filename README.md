<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Asynchronous Bookmark Metadata Fetcher

This is a minimal API built with Laravel 11, designed to allow users to submit web links (bookmarks) and fetch their metadata (title and description) asynchronously using RabbitMQ as a message broker. The project is containerized with Docker for easy setup and deployment.

## Features

- **Bookmark Submission**: Submit a URL via API, which is stored in the database and triggers an asynchronous metadata fetch.
- **Asynchronous Metadata Fetching**: Uses RabbitMQ to process bookmark metadata retrieval in the background, ensuring scalability and performance.
- **Dockerized Environment**: Includes a fully configured docker-compose.yml to run the application, RabbitMQ, and database seamlessly.

## Installation

1. Clone this repository
2. Navigate to the project directory
3. Create .env file from .env.example
4. Run docker compose command
   1. Deploy: `docker compose up --build -d`
   2. Local: `docker compose -f docker-compose.local.yml up --build -d`
5. Run `docker-compose exec app php setup`

## Url
- Deploy: http://localhost:80
- Local: http://localhost:8000
- Phpmyadmin: http://localhost:8080
- Api Documentation: http://localhost:8000/docs

### Framework Documentation
- [Laravel 11](https://laravel.com/docs/11.x)

### Application useful commands
- Update api documentation
  - `docker compose exec app php artisan scribe:generate`
- Run tests
  - `docker compose exec app php artisan test`


### Basic docker compose commands
- Build or rebuild services
    - `docker compose build`
- Create and start containers
    - `docker compose up -d`
- Stop and remove containers, networks
    - `docker compose down`
- Stop all services
    - `docker compose stop`
- Restart service containers
    - `docker compose restart`
- Run a command inside a container
    - `docker compose exec [container] [command]`

### Useful Laravel Commands
- Remove the configuration cache file
    - `php artisan config:clear`
- Flush the application cache
    - `php artisan cache:clear`
- Clear all cached events and listeners
    - `php artisan event:clear`
- Delete all the jobs from the specified queue
    - `php artisan queue:clear`
- Remove the route cache file
    - `php artisan route:clear`
- Clear all compiled view files
    - `php artisan view:clear`
- Remove the compiled class file
    - `php artisan clear-compiled`
- Remove the cached bootstrap files
    - `php artisan optimize:clear`
- Delete the cached mutex files created by scheduler
    - `php artisan schedule:clear-cache`
- Flush expired password reset tokens
    - `php artisan auth:clear-resets`
