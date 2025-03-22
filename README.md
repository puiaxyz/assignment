# Laravel POS System

This is a Laravel-based **Point of Sale (POS) System** with **Filament Admin Panel** integration made by Thuamthangpuia MZU B.tech as part of the fulfillment of campus recruitment by Lailen Pvt Ltd. It supports **product management, sales tracking, barcode scanning**, and **invoice generation** and the system is mainly based around Filament.

Developed during 12th March-19th March 
## 📌 Prerequisites

Ensure you have the following installed:
- **PHP 8.1+**
- **Composer**
- **MySQL** 
- **Git**

## 🚀 Installation & Setup

### 1️⃣ Clone the Repository
```sh
git clone https://github.com/puiaxyz/assignment.git
cd assignment
```

### 2️⃣ Install Dependencies
Run the following command to install PHP dependencies:
```sh
composer install
```
```sh
npm install && npm run build
```

### 3️⃣ Configure Environment
Copy the example `.env` file and set up your database credentials:
```sh
cp .env.example .env
```
Edit `.env` file:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4️⃣ Generate Application Key
```sh
php artisan key:generate
```

### 5️⃣ Run Migrations & Seed Database
```sh
php artisan migrate --seed
```
This creates the necessary tables and seeds test data.

### 6️⃣ Serve the Application
```sh
php artisan serve
```
Visit **http://127.0.0.1:8000** to access the application.

### 7️⃣ Login Info 
```sh
ADMIN
email:admin@test.com
password: password

Normal User
email:test@test.com   
password: password
```
### Contact Info

Thuamthangpuia@gmail.com   



---
###Work in Progress
Note that this project is still under development, with several features open to refinement and improvement. However, the core functionalities are operational. Users can already perform essential transactions, and the system is available for basic usage. 

