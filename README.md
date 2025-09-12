# Tiny crm

![Tiny CRM dashboard screenshot](screenshots/Dashboard%20Screenshot.png "Tiny CRM dashboard screenshot")

[![Tests](https://github.com/frikishaan/tiny-crm/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/frikishaan/tiny-crm/actions/workflows/run-tests.yml)

This is a small and Open-source CRM application created using the [Filament PHP](https://filamentphp.com/).

## Tech stack

-   PHP (Laravel)
-   Filament PHP

## Local Installation

### Clone the repository

```bash
git clone https://github.com/frikishaan/tiny-crm.git
```

### Install dependencies

```bash
composer install #installing php dependencies

npm install # installing the JS dependencies

npm run build # to build the frontend assets
```

### Create environment file

```bash
cp .env.example .env
```

### Generate application key

```bash
php artisan key:generate
```

### Update environment variables

Replace the following values in `.env` file with your database credentials. For example -

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tiny_crm
DB_USERNAME=postgres
DB_PASSWORD=password
```

### Migrate the database

```bash
php artisan migrate
```

Optionally, you can create the dummy data by running the seeder as -

```bash
php artisan db:seed
```

<!-- ## You might also like
If you like tiny-crm, check out my other project [Lynx](https://github.com/frikishaan/lynx), an open-source link shortener. It’s a great tool for creating and managing shortened links, perfect for tracking campaigns and sharing links more efficiently.
-->

<!-- ## Need assistance?

If you need help customizing this application or want to create your own application like this, contact me on [upwork](https://www.upwork.com/services/product/consulting-hr-a-customer-crm-software-1651120102232907776?ref=project_share) or on [LinkedIn](https://www.linkedin.com/in/ishaan-s/). -->
