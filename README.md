<p align="center">
  <img src="public/images/eteeap_logo.png" width="160" alt="BU-ETEEAP Logo">
</p>

<h2 align="center">BU-ETEEAP Management & Analytics System</h2>

<p align="center">
  A web-based information system developed to support the
  <strong>Expanded Tertiary Education Equivalency and Accreditation Program (ETEEAP)</strong>
  of Bicol University. Built using Laravel to manage user accounts,
  academic records, and administrative analytics efficiently.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Built%20With-Laravel-red" alt="Laravel">
  <img src="https://img.shields.io/badge/System-Academic-blue" alt="Academic System">
  <img src="https://img.shields.io/badge/Status-Active-success" alt="Status">
</p>

---

## About This Project

The BU-ETEEAP Management & Analytics System is a Laravel-based web application developed to streamline administrative processes of the ETEEAP program at Bicol University.

It provides:

- Secure authentication and role-based access
- User and account management
- Academic records monitoring
- Dashboard analytics and reporting
- Profile and credential management

The system is designed to improve efficiency, data accuracy, and decision-making for administrators and coordinators.

---

## System Requirements

Make sure you have installed:

- PHP >= 8.0
- Composer
- MySQL 
- Git
- XAMPP

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

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=BU_ETEEAP
DB_USERNAME=root
DB_PASSWORD= 
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
