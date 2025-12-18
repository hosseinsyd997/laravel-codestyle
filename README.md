# Laravel User Management API

This is a **Laravel 12** project demonstrating a clean architecture for managing users, countries, and currencies. It leverages **Repository-Service pattern**, **API integrations**, **custom validation**, **resource responses**, and **scheduled tasks**.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Models and Relationships](#models-and-relationships)
- [API Endpoints](#api-endpoints)
- [Validation](#validation)
- [Resources](#resources)
- [Testing](#testing)
- [Scheduler & Cron](#scheduler--cron)
- [Docker](#docker)
- [External API Integration](#external-api-integration)
- [Usage](#usage)
- [Notes](#notes)

---

## Features

- Manage Users, Countries, and Currencies
- API-driven updates of countries and currencies
- Repository-Service pattern for clean code
- API resources for structured JSON responses
- Scheduled commands to update data automatically
- Dockerized environment with cron for Laravel scheduler
- Full-featured tests for API endpoints

---

## Tech Stack

- PHP 8.3
- Laravel 12
- SQLite (for tests)
- Docker & Docker Compose
- REST Countries API (external integration)
- PHPUnit for testing
- Laravel Resource & FormRequest validation
- Cron & Laravel Scheduler

---

## Architecture

- **Models:** User, Country, Currency
- **Repositories:** Handle all database interactions
- **Services:** Handle business logic
- **Controllers:** Handle API requests, return Resource responses
- **Requests:** Custom FormRequest classes for validation
- **Resources:** Structure JSON output for API endpoints
- **Commands:** Artisan commands for scheduled updates

---

## Models and Relationships

- **User**
  - `id`, `name`, `email`, `country_id`
  - Relationship: `belongsTo` Country
- **Country**
  - `id`, `name`, `code`, `currency_id`
  - Relationship: `hasMany` Users, `belongsTo` Currency
- **Currency**
  - `id`, `code`, `name`, `symbol`
  - Relationship: `hasMany` Countries

---

## API Endpoints

| Method | Endpoint                    | Description                         |
|--------|-----------------------------|-------------------------------------|
| GET    | `/api/users`                | List all users with optional filters|
| POST   | `/api/users`                | Create a new user                   |
| PUT    | `/api/users/{id}`           | Update an existing user             |
| DELETE | `/api/users/{id}`           | Delete a user                       |
| GET    | `/api/countries-external`   | Fetch countries & currencies from external API |

**Filters for Users:**

- `country` → Filter by country name (case-insensitive)
- `currency` → Filter by currency code (case-insensitive)
- `sortBy` → Sort by `id`, `name`, `email`

---

## Validation

- `StoreUserRequest` and `UpdateUserRequest` ensure:
  - Name is required and a string
  - Email is required, valid format, and unique
  - Country exists in database
- `ListUsersRequest`:
  - Filters are normalized (`ucfirst` for country, `upper` for currency)
  - Validates existence in DB

---

## Resources

- `DetailUserResource` → Return user data with nested country and currency
- `DetailCountryResource` → Return country with currency
- `DetailCurrencyResource` → Return currency details
- External API resource returns:
```json
{
  "country": "Iran",
  "currency": "IRR"
}
```

---

## Testing

Feature tests using `RefreshDatabase`:
- `ListUsersTest`
- `StoreUserTest`
- `UpdateUserTest`
- `DeleteUserTest`
- Factories for User, Country, Currency
- Assert JSON structure and database changes

Unit tests:
- `UserServiceTest` (store, update, delete user)
- `UserRepositoryTest` (repository methods)
- Seed required `Country` and `Currency` data using factories


Run a single test:
```bash
php artisan test --filter=StoreUserTest
```

Run all tests:
```bash
php artisan test
```

---

## Scheduler & Cron

Artisan command `UpdateCountriesAndCurrencies`:
- Fetches countries and currencies from external API
- Updates database using upsert inside `DB::transaction()`

Laravel Scheduler setup:
```php
$schedule->command(UpdateCountriesAndCurrencies::class)->daily();
```

Docker cron setup:
```dockerfile
RUN echo "* * * * * cd /var/www && php artisan schedule:run >> /var/log/cron.log 2>&1" > /etc/cron.d/laravel-cron
CMD ["sh", "-c", "cron && php-fpm"]
```

---

## Docker

- PHP 8.3 FPM with cron and sqlite
- Composer installed in container
- `docker-compose.yml` for backend
- Volumes mapped to `/var/www`

**Docker commands:**

Build & start container:
```bash
docker compose up -d --build
```

Run migrations:
```bash
docker exec laravel_app php artisan migrate
```

Seed database (optional):
```bash
docker exec laravel_app php artisan db:seed
```

Run tests:
```bash
docker exec laravel_app php artisan test
```

---

## External API Integration

- Uses **REST Countries API**
- Returns a simplified resource for countries & currencies

Example response:
```json
[
  {
    "country": "Iran",
    "currency": "IRR"
  },
  {
    "country": "Germany",
    "currency": "EUR"
  }
]
```

---

## Usage

1. Clone the repository:
```bash
git clone <repo-url>
cd <repo-folder>
```

2. Build and start Docker containers:
```bash
docker up -d --build
```

3. Run migrations:
```bash
docker exec laravel_app php artisan migrate
```

4. Seed database (optional):
```bash
docker exec laravel_app php artisan db:seed
```

API is available at `http://localhost:8000/api/...`

---

## Notes

- **Repository-Service pattern** ensures clean separation of concerns
- **Requests** handle input validation and normalization
- **Resources** enforce API response structure
- **Scheduler** allows automatic updates from external API
- **Docker** ensures environment parity and cron execution
- **Factories** simplify testing and data generation

**Author:** Hossein Seyyedi  
**License:** MIT