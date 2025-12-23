# TMS MVP - Transportation Management System

A modern, polished Transportation Management System built with Laravel 11, featuring real-time driver tracking, load management, and document handling.

## 🚀 Features

### Authentication & RBAC
- Three user roles: **Admin**, **Dispatcher**, **Driver**
- Permission-based access control using Spatie Laravel Permission
- Secure authentication with Laravel's built-in system

### Load Management
- Complete CRUD operations for loads
- Filter by status and assigned driver
- Assign drivers to loads
- Status workflow: Created → Assigned → In Transit → Delivered
- Tabbed load detail view (Overview | Documents | Tracking)

### Real-Time Driver Tracking
- Browser geolocation API integration
- 5-10 second polling intervals
- Driver location history (breadcrumbs)
- Live dispatch map with Leaflet + OpenStreetMap

### Document Management
- Upload PDF/images (POD, Photos, Other)
- Private file storage with authorized downloads
- Document preview functionality
- Type categorization and metadata

### UI/UX
- Clean white enterprise design ("Trucking OS" style)
- Left icon-based sidebar navigation
- Top header with breadcrumbs
- Status pills with color coding
- Responsive tables
- Alpine.js interactive components

## 📋 Requirements

- PHP 8.3+
- Composer
- Node.js & NPM
- MySQL 8.0+
- Docker & Docker Compose (for containerized deployment)

## 🛠 Installation & Setup

### Local Development (Docker)

1. **Clone the repository**
```bash
git clone <your-repo-url>
cd TMS-2.0
```

2. **Copy environment file**
```bash
cp .env.example .env
```

3. **Install PHP dependencies**
```bash
composer install
```

4. **Install Node dependencies**
```bash
npm install
```

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Start Docker containers**
```bash
docker-compose up -d
```

7. **Run migrations and seed database**
```bash
docker-compose exec app php artisan migrate:fresh --seed
```

8. **Build frontend assets**
```bash
npm run build
```

9. **Access the application**
- Open browser to: `http://localhost:8080`
- Login credentials below

### Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Dispatcher | dispatcher@example.com | password |
| Driver | driver@example.com | password |

## 🌐 Deployment Options

### Option A: Render.com (Recommended for Quick Deploy)

1. **Prepare your repository**
   - Push code to GitHub
   - Ensure `.env.example` is committed

2. **Create new Web Service on Render**
   - Connect your GitHub repository
   - Set build command: `composer install && npm install && npm run build && php artisan migrate --force`
   - Set start command: `php artisan serve --host=0.0.0.0 --port=$PORT`

3. **Set Environment Variables**
```
APP_NAME=TMS MVP
APP_ENV=production
APP_KEY=<generate with: php artisan key:generate --show>
APP_DEBUG=false
APP_URL=https://your-app.onrender.com

DB_CONNECTION=mysql
DB_HOST=<render-mysql-host>
DB_PORT=3306
DB_DATABASE=<your-db-name>
DB_USERNAME=<your-db-user>
DB_PASSWORD=<your-db-password>

SESSION_DRIVER=database
CACHE_STORE=database
FILESYSTEM_DISK=local
```

4. **Create MySQL database**
   - Add PostgreSQL or MySQL addon in Render
   - Copy credentials to environment variables

5. **Deploy**
   - Click "Deploy" and wait for build
   - Run seed command via Render Shell: `php artisan db:seed`

### Option B: DigitalOcean App Platform

1. **Create new App**
   - Connect GitHub repository
   - Select "Web Service" component

2. **Configure Build**
   - Build Command: `composer install --no-dev && npm install && npm run build`
   - Run Command: `heroku-php-apache2 public/`

3. **Add MySQL Database**
   - Create Managed MySQL Database
   - Note connection details

4. **Set Environment Variables** (same as Render above)

5. **Post-Deploy Commands**
```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Option C: VPS (Ubuntu + Nginx + Docker)

1. **SSH into your VPS**
```bash
ssh user@your-vps-ip
```

2. **Clone repository**
```bash
git clone <repo-url> /var/www/tms
cd /var/www/tms
```

3. **Set up environment**
```bash
cp .env.example .env
nano .env  # Edit database credentials
```

4. **Run with Docker**
```bash
docker-compose up -d
```

5. **Install dependencies & migrate**
```bash
docker-compose exec app composer install --no-dev
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:fresh --seed
docker-compose exec app php artisan storage:link
```

6. **Configure Nginx (if not using Docker nginx)**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/tms/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

7. **Set permissions**
```bash
sudo chown -R www-data:www-data /var/www/tms/storage
sudo chown -R www-data:www-data /var/www/tms/bootstrap/cache
sudo chmod -R 775 /var/www/tms/storage
sudo chmod -R 775 /var/www/tms/bootstrap/cache
```

## 🔐 Security Features

- ✅ Server-side RBAC enforcement
- ✅ FormRequest validation on all inputs
- ✅ Rate-limited location endpoint (60 req/min)
- ✅ Private file storage with authorization
- ✅ CSRF protection on all forms
- ✅ Password hashing with bcrypt
- ✅ SQL injection prevention via Eloquent ORM
- ✅ No API keys required (OpenStreetMap is free)

## 📁 Project Structure

```
TMS 2.0/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php
│   │   │   ├── LoadController.php
│   │   │   ├── DriverController.php
│   │   │   ├── DispatchController.php
│   │   │   └── DocumentController.php
│   │   └── Requests/
│   │       ├── StoreLoadRequest.php
│   │       ├── StoreLocationRequest.php
│   │       └── UploadDocumentRequest.php
│   └── Models/
│       ├── User.php
│       ├── Load.php
│       ├── LoadDocument.php
│       ├── DriverLocation.php
│       └── DriverBreadcrumb.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── RolePermissionSeeder.php
│       └── LoadSeeder.php
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/
│       ├── layouts/app.blade.php
│       ├── components/ (sidebar, breadcrumbs, status-pill)
│       ├── auth/login.blade.php
│       ├── loads/ (index, create, show)
│       ├── driver/dashboard.blade.php
│       └── dispatch/map.blade.php
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## 🗄 Database Schema

### Key Tables

**loads**
- reference_no, pickup/delivery addresses, GPS coordinates
- status enum workflow
- assigned_driver_id foreign key

**load_documents**
- file metadata (path, mime_type, size)
- type categorization (POD/PHOTO/OTHER)
- uploaded_by user tracking

**driver_locations**
- Latest GPS position per driver
- captured_at timestamp

**driver_breadcrumbs**
- Historical tracking per load
- load_id + user_id composite tracking

## 🚦 Routes Overview

| Method | URI | Description | Role |
|--------|-----|-------------|------|
| GET | /login | Login page | Guest |
| GET | /loads | List all loads | Admin/Dispatcher |
| GET | /loads/{id} | Load details | All |
| POST | /loads | Create load | Admin/Dispatcher |
| PUT | /loads/{id}/assign | Assign driver | Admin/Dispatcher |
| GET | /driver | Driver dashboard | Driver |
| POST | /driver/location | Submit GPS | Driver |
| GET | /dispatch/map | Live tracking map | Admin/Dispatcher |
| POST | /loads/{id}/documents | Upload file | All |

## 💻 Tech Stack

- **Backend**: Laravel 11, PHP 8.3, MySQL 8
- **Frontend**: Blade, Tailwind CSS 3, Alpine.js 3
- **Auth**: Spatie Laravel Permission
- **Maps**: Leaflet.js + OpenStreetMap
- **Container**: Docker + Nginx
- **Build**: Vite

## 📞 Support

For issues or questions:
1. Check the logs: `docker-compose logs -f app`
2. Verify database: `docker-compose exec mysql mysql -u tms_user -p`
3. Clear cache: `php artisan optimize:clear`

## 📝 License

MIT License - Feel free to use this for personal or commercial projects.

---

**Made with ❤️ for efficient logistics management**
