# 🎮 GameShop CRM

Welcome to **GameShop CRM**, a full-stack application built with **Laravel** (Backend) and **Vue.js** (Frontend). This system allows **PS4/PS5 game stores** to efficiently manage **customer requests** for services like **repairs, system updates**, and **game installations**. 🚀

## 🛠 Features

### Backend (Laravel)
- RESTful API to manage customer requests
- Customer and service management
- Service categories and pricing 💸
- Authentication and authorization 🔐
- Order and service management 📝

### Frontend (Vue.js)
- Dynamic user interface for customer interactions 🖥️
- API communication with the backend
- Manage orders and track request status 📦

## ⚙️ Installation

### Prerequisites

- **PHP** 7.4 or higher
- **Composer** for PHP dependencies
- **Node.js** and **npm** for frontend dependencies

### Steps to Install

1. **Clone the Repository**

   First, clone the project from GitHub:

   ```bash
   git clone https://github.com/mohammadRfn/GameShops.git
   cd GameShops
cd backend
composer install
Generate the application key:

php artisan key:generate
Run the migrations to set up the database:

php artisan migrate
Go to the frontend directory and install the required Node.js dependencies:

cd ../frontend
npm install
Start the Development Servers

Backend (Laravel):

php artisan serve


Frontend (Vue.js):

npm run dev
