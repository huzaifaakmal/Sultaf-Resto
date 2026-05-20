# Sultaf Restaurant - Online Ordering Demo

A vintage-style Arabic dining website built for a school project. This application allows customers to browse authentic Middle Eastern cuisine, add items to a shopping cart, and simulate a QR-code based payment process.

## 📍 Location
**Address:** Jalan Malioboro No. 123, Kotagede, Yogyakarta, Indonesia  
**Phone:** +62 812 3456 7890

---

## 🚀 Features

### 🛒 Online Ordering System
- **Real-time Shopping Cart:** Add or remove items from the cart with quantity selection.
- **Guest Checkout:** No account required—perfect for quick restaurant orders.
- **IDR Pricing:** All prices are displayed in Indonesian Rupiah (Rp).

### 💳 Mock QR Payment Flow (Demo Mode)
- **QR Code Generation:** Each order generates a unique QR code containing order details.
- **Payment Simulation:** A dedicated "Simulate Payment" feature to demonstrate the successful transaction flow for presentations.
- **Success Confirmation:** A professional success page showing the order status.

### 🍱 Authentic Menu
- **High-Quality Images:** Local AI-style images for dishes like Mandi Lamb, Chicken Biryani, Samosas, and more.
- **Categories:** Appetizers, Main Courses, and Desserts.

### 🛠 Technical Stack
- **Framework:** Laravel 8 (PHP 8.0)
- **Database:** MySQL (using `sultaf_db`)
- **Frontend:** Blade Templates & Vanilla CSS for a vintage aesthetic.

---

## 📦 Installation & Setup

To run this project on your local machine, follow these steps:

### 1. Prerequisites
- **XAMPP** (with PHP 8.0 and MySQL)
- **Composer**
- **Node.js & NPM**

### 2. Clone the Repository
```bash
git clone https://github.com/huzaifaakmal/Sultaf-Resto.git
cd sultaf-web
```

### 3. Setup Environment
1. Create a database named `sultaf_db` in your MySQL (HeidiSQL/phpMyAdmin).
2. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```
3. Update the `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sultaf_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 4. Install Dependencies
```bash
composer install
npm install
```

### 5. Initialize Database
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

### 6. Run the Application
```bash
php artisan serve
```
Visit the app at: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🎓 School Project Demo Guide
1. **Browse:** Select "Add to Cart" on items like Mandi Lamb or Biryani.
2. **Review:** Click the "Cart" link in the navigation bar.
3. **Checkout:** Fill in the customer name and phone.
4. **Payment:** Show the generated QR code.
5. **Simulate:** Click the green "Simulate Payment Success" button to complete the demo flow.

---

**Developed by:** huzaifaakmal  
**Project for:** School Demo / Portfolio
