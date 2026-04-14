<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## About This Project

This is a Laravel-based web application (ETEEAP System) developed for academic and system requirements. It includes authentication, UI pages, and database integration.

---

## System Requirements

Make sure you have installed:

- PHP >= 8.x
- Composer
- MySQL 
- Git

---

## Project Setup Guide (Step-by-Step)

### 1. Clone the repository

```bash
git clone https://github.com/YOUR_USERNAME/BU_ETEEAP.git
cd BU_ETEEAP
```

### 2. Install PHP dependencies

```bash
composer install
```
### 3. Copy environment file

```bash
cp .env.example .env
```

### 4. Configure .env
Open .env file and update:

```bash
APP_NAME=ETEEAP
APP_URL=http://127.0.0.1:8000

DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Run database migrations

```bash
php artisan migrate
```

### 7. Seed Database

```bash
php artisan db:seed
```

### 8. Install frontend dependencies

```bash
npm install
npm run dev
```

### 9. Run Laravel server

```bash
php artisan serve
```
