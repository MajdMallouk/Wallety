# 💸 Wallety — Modern Money Transfer & Exchange Platform

Wallety is a Laravel-based web application that allows users to send, receive, and deposit money easily using usernames or emails. It also features QR code scanning and generation for seamless personal transactions and includes a built-in currency exchange system.

---

## 🚀 Features

- **🔐 User Authentication** – Secure login, registration, and session management
- **💰 Money Transfers** – Send and receive funds via username or email
- **🏦 Deposits & Wallets** – Manage balances and deposits conveniently
- **🔄 Currency Exchange** – Convert between supported currencies in real-time
- **📱 QR Code Transactions** – Instantly send or receive funds with scannable QR codes
- **🌍 Location Integration** – Uses `stevebauman/location` for user geolocation tracking
- **⚙️ RESTful API** – Easy integration for mobile or frontend clients
- **🧩 Modular Architecture** – Clean, maintainable Laravel structure

---

## Requirements

- PHP 8.1+
- Laravel 11.x
- MySQL
- Node.js & npm (for Tailwind compilation)

---

## Installation

1. **Install PHP dependencies**
```bash
    composer install
```
2. **Create .env file**
```bash 
    cp .env.example .env
```
Set your database connection (inside .env in case you're wondering):
```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=wallety
    DB_USERNAME=root
    DB_PASSWORD=
```
3. **Generate application key**
```bash
    php artisan key:generate
```
4. **Run migrations**
```bash
    php artisan migrate
```
5. **Serve the application**
```bash
    php artisan serve
```
Visit [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

> **Note:** Wallety is open source under the MIT License. You're free to fork, fix, or improve the project — contributions are encouraged!