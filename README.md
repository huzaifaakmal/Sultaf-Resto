# Sultaf Restaurant 🕌

Sultaf Restaurant is a premium, vintage-themed Arabic dining platform. It features a rich, aesthetic user interface for customers and a secure management system for administrators.

## 🌟 Key Features

### For Customers
*   **Authentic UI**: A vintage, parchment-themed interface inspired by traditional Arabic hospitality.
*   **Dynamic Menu**: Browse categories (Main Course, Appetizers, Desserts, Drinks) with real-time price updates.
*   **Shopping Cart**: Easily add, manage, and remove items before checkout.
*   **QRIS Payment**: Integrated real-world QRIS payment system for seamless transactions.
*   **Order Success Tracking**: Clear verification and success screens after payment simulation.

### For Administrators
*   **Secure Dashboard**: Protected by custom `IsAdmin` middleware.
*   **Menu Management**:
    *   **Create**: Add new menu items with image uploads.
    *   **Read**: View all current offerings in a clean, organized table.
    *   **Update**: Modify food prices instantly.
    *   **Delete**: Remove items no longer available.
*   **Dynamic Categories**: Fully relational category management.

## 🛠️ Technical Stack

*   **Framework**: Laravel 8 (PHP)
*   **Database**: MySQL / MariaDB (Relational Architecture)
*   **Frontend**: Blade Templating, Vanilla CSS (Modern aesthetic with vintage variables)
*   **Security**: Bcrypt Password Hashing, CSRF Protection, Session Management (Database-driven)

## 🚀 Installation & Setup

1.  **Clone the repository**
2.  **Install dependencies**:
    ```bash
    composer install
    npm install
    ```
3.  **Environment Setup**:
    *   Copy `.env.example` to `.env`.
    *   Configure your database credentials.
    *   Generate application key: `php artisan key:generate`.
4.  **Database Migration & Seeding**:
    ```bash
    php artisan migrate:refresh --seed
    ```
5.  **Run the application**:
    ```bash
    php artisan serve
    ```

## 🔐 Admin Credentials (Default)
*   **Login URL**: `/admin/login`
*   **Username**: `admin`
*   **Password**: `admin`

## 📖 Detailed Documentation
For a deep dive into the technical concepts (MVC, Relational Database, Security, etc.) used in this project, please refer to the [DOCUMENTATION.md](./DOCUMENTATION.md) file.

---
*Created for the Sultaf Restaurant Group Project - Yogyakarta, 2026.*
