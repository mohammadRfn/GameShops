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

- **PHP** 8.4
- **Composer** for PHP dependencies
- **Node.js** and **npm** for frontend dependencies

### Steps to Install

1. **Clone the Repository**

   First, clone the project from GitHub:

   ```bash
   git clone https://github.com/mohammadRfn/GameShops.git
   cd GameShops
   
2. **Install Backend Dependencies (Laravel)**

   ```bash
   cd backend
   composer install

3. **Setup Environment File**
   ```bash
   cp .env.example .env
   php artisan key:generate

4. **Run Database Migrations**
   ```bash
   php artisan migrate

5. **Install Frontend Dependencies (Vue.js)**
    ```bash
    cd ../frontend
    npm install

6. **Start the Development Servers**
    ```bash
    php artisan serve
    npm run dev
