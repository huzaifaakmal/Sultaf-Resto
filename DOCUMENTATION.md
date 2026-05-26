# Sultaf Restaurant - Technical Project Documentation

This document provides a detailed overview of the technical implementation for the **Sultaf Restaurant** project. Our team used Laravel to build this platform, focusing on a clean architecture and secure data management.

---

## 1. Core Architecture: MVC (Model-View-Controller)
The project follows the MVC design pattern to separate logic from the presentation layer.

*   **Models (`app/Models`)**: These classes interact with the database tables.
    *   *Example*: `MenuItem.php` maps to our menu items data.
*   **Views (`resources/views`)**: These are the Blade templates that define the user interface.
    *   *Example*: `home.blade.php` is the main page customers see.
*   **Controllers (`app/Http/Controllers`)**: These handle the request logic and pass data from models to views.

---

## 2. Database Design & Relational Mapping
We have moved from a "Flat" database to a "Relational" database.

### Relational Concepts Used:
*   **One-to-Many Relationship**: One `Category` has many `MenuItems`.
    *   *Why?* If we want to rename "Main Course" to "Traditional Dishes," we only change it in one place (the `categories` table), and every linked menu item updates automatically.
*   **Foreign Keys (`category_id`)**: A column in the `menu_items` table that "points" to the ID of a category. It acts as a bridge between the two tables.
*   **Database Normalization**: This is the process of organizing data to reduce redundancy. By separating categories into their own table, we follow the **Third Normal Form (3NF)**.

### The Tables:
1.  **`users`**: Stores admin credentials.
2.  **`categories`**: Stores category names (Drinks, Desserts, etc.).
3.  **`menu_items`**: Stores food details and links to a `category_id`.
4.  **`orders` & `order_items`**: Handles checkout data.
5.  **`sessions`**: Stores active login data (stored in the database for better reliability).

---

## 3. Security & Authentication

### CSRF Protection (419 Error)
You might have seen a "419 Page Expired" error.
*   **CSRF (Cross-Site Request Forgery)**: A security attack where someone tries to submit a form on your behalf.
*   **The Token**: Laravel generates a secret `@csrf` token for every session. Every form must include this token. If the token is missing or expired, Laravel throws a 419 error to protect the site.

### Middleware (`app/Http/Middleware`)
Middleware is a "Filter" that sits between the user and the controller.
*   **`IsAdmin` Middleware**: We created this to check if a user is logged in AND has the "admin" role. If they don't, it blocks them from seeing the dashboard.

---

## 4. Advanced Concepts

### Eager Loading (`with()`)
*   **The Problem (N+1)**: If you have 10 menu items and you ask for the category name for each one, the database has to do 11 "searches" (1 for items + 10 for categories).
*   **The Solution**: Using `MenuItem::with('category')->get()`. This tells Laravel to fetch everything in just **2 searches**, making the site much faster.

### Blade Templating & Components
*   **`@extends('layouts.app')`**: We use a master layout so we don't have to rewrite the Header and Footer for every page.
*   **`asset()` helper**: This function generates the correct URL for images and CSS. It ensures that images load correctly whether you are on `localhost` or a real server.

### Migration & Seeders
*   **Migrations**: Think of these as "Version Control" for your database. Instead of sharing SQL files, we write PHP scripts that define the table structure.
*   **Seeders**: Scripts that automatically fill the database with "dummy" or "initial" data (like our 7 menu items and the admin account).

---

## 5. Summary of Development Workflow
1.  **Request**: User goes to `/admin`.
2.  **Middleware**: `IsAdmin` checks if they are logged in.
3.  **Route**: `web.php` directs them to `AdminMenuController@index`.
4.  **Controller**: Asks `MenuItem` and `Category` models for data.
5.  **View**: `dashboard.blade.php` receives the data and renders the HTML.

---

## Conclusion
Our team has focused on building a scalable and secure foundation for the Sultaf Restaurant platform. By following modern Laravel practices like relational database design and secure authentication, we have ensured that the system is both maintainable and robust.
