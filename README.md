# 🚗 Car Management & Marketplace System

A Laravel-based web application for managing car listings, images, features, and user authentication. The system supports car uploads with images, feature selection and modern authentication (including social login), car search & filtering and pagination.

---

## 📌 Project Overview

This project is designed to allow users to:

* Register and log in (standard + social authentication)
* Create and manage car profiles
* Upload multiple car images
* Select car features using checkboxes
* View cars with responsive image previews
* Car search & filtering
* Pagination

  
It is built with **Laravel**, uses **MySQL**, and follows Laravel best practices for file storage and authentication.

---

## 🛠️ Tech Stack

* **Backend:** Laravel (PHP)
* **Frontend:** Blade, Bootstrap, JavaScript
* **Database:** MySQL
* **Authentication:** Laravel Auth + Socialite (Google & Facebook)
* **File Storage:** Laravel Filesystem (public disk)
* **Server:** XAMPP / Apache

---

## 📂 Project Structure (Key Parts)

```
app/
 ├── Http/Controllers/
 ├── Models/
resources/
 ├── views/
public/
 └── storage → symlink to storage/app/public
storage/
 └── app/public/cars/
routes/
 └── web.php
```

---

## 🔐 Authentication Features

* Email & password authentication
* Facebook login (Laravel Socialite)
* Google login (Laravel Socialite)

> Note: Facebook login requires correct OAuth redirect URIs and HTTPS in production.
> Note: Faceebook login uses URI "login http://localhost:8000/login" for it to work
> Note: Google login uses URI "login http://127.0.0.1:8000/login" for it to work

---

## 🚘 Car Management Features

* Create car listings
* Upload car images
* Store images in `storage/app/public/cars`
* Automatically generate public URLs using `storage:link`
* Select car features using checkboxes
* View car details with image previews

### Image Upload Logic

```php
$path = $request->file('image')->store('cars', 'public');
```

Stored in database as:

```
cars/filename.jpg
```

Displayed using:

```blade
<img src="{{ asset('storage/' . $image->image_path) }}">
```

---

## 🖼️ File Storage Setup (IMPORTANT)

Run this command once after cloning the project:

```bash
php artisan storage:link
```

This creates a symbolic link:

```
public/storage → storage/app/public
```

---

## ⚙️ Installation Guide

### 1️⃣ Clone the repository

```bash
git clone https://github.com/your-username/car-project.git
cd car-project
```

### 2️⃣ Install dependencies

```bash
composer install
```

### 3️⃣ Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials.

---

### 4️⃣ Run migrations

```bash
php artisan migrate:fresh --seed
```
> For Facebook authentication to function correctly, the email column in the users table must be nullable, as Facebook may not always return an email address.
> To support very large mileage values when adding a car, the mileage column in the cars table should be defined as BIGINT instead of INT.

---

### 5️⃣ Start the application

```bash
php artisan serve
```

Visit:

```
http://localhost:8000
```

---

## 🔑 Environment Variables (Example)

```env
APP_NAME="Car Project"
APP_URL=http://localhost:8000

DB_DATABASE=car_project
DB_USERNAME=root
DB_PASSWORD=

FACEBOOK_CLIENT_ID=your_id
FACEBOOK_CLIENT_SECRET=your_secret
```

---

## 🧪 Common Issues & Fixes

### Images not displaying

* Ensure `php artisan storage:link` was run
* Ensure DB paths do NOT start with `storage/`
* Access image directly via `/storage/cars/filename.jpg`

### Git commit error (email not detected)

```bash
git config --global user.name "Your Name"
git config --global user.email "your@email.com"
```

---

## 🚀 Future Improvements
* Integrate user roles
* Image optimization
* Admin dashboard
* API endpoints
* Deployment to production server

---

## 👨‍💻 Author

**Olamide Ola**
2026 / Full Stack Developer

---

## 📄 License

This project is open-source and available for learning and development purposes.
