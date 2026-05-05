# Live School - World-Class Free Online Education Platform

## 🚀 Project Overview

Live School is a modern Laravel-based online education platform designed to provide world-class education for free. The platform supports multiple user roles (Student, Instructor, Admin) and includes a complete learning management system with courses, lessons, enrollments, and progress tracking.

## 📋 Features Implemented

### Phase 1: Core Platform & Authentication ✅

#### User Authentication & Roles
- Role-based authentication (Student, Instructor, Admin)
- Breeze authentication scaffolding with email verification
- Role-aware dashboards and access controls
- User registration with role selection

#### Course Management
- Create categories for course organization
- Course creation and publishing workflow
- Lesson management with multiple content types (video, text, quiz, resource)
- Course visibility status (draft, published, archived)
- Lesson ordering and hierarchy

#### Learning Features
- Course discovery and browsing with category filters
- Detailed course pages with instructor info and curriculum preview
- Free course enrollment system
- Student enrollment tracking
- Progress tracking per enrollment
- Completion status tracking

#### Role-Specific Dashboards
- **Student Dashboard**: View enrolled courses, track progress, continue learning
- **Instructor Dashboard**: Manage courses, view student counts, monitor course performance
- **Admin Dashboard**: Platform statistics, recent courses, content moderation controls

#### Frontend Design
- Modern startup-style UI with Tailwind CSS
- Responsive design (mobile, tablet, desktop)
- Dark-mode ready color system
- Hero landing page with CTAs
- Intuitive navigation and user experience

## 🛠️ Technology Stack

- **Framework**: Laravel 12
- **Frontend**: Blade templates with Tailwind CSS 4.0
- **Database**: SQLite (development), can switch to PostgreSQL/MySQL for production
- **Authentication**: Laravel Breeze
- **Build Tool**: Vite
- **PHP Version**: 8.2+

## 📦 Project Structure

```
app/
├── Models/
│   ├── User.php                    # User model with roles
│   ├── Category.php                # Course categories
│   ├── Course.php                  # Courses with instructor relation
│   ├── Lesson.php                  # Course lessons
│   └── Enrollment.php              # Student course enrollments
├── Http/Controllers/
│   ├── CourseController.php        # Course listing and details
│   └── DashboardController.php     # Role-based dashboards
└── Http/Middleware/                # Authentication middleware

database/
├── migrations/                     # Database schema
└── seeders/
    ├── DatabaseSeeder.php
    ├── CategorySeeder.php          # Sample course categories
    └── CourseSeeder.php            # Sample courses with lessons

resources/views/
├── welcome.blade.php               # Landing page
├── courses/
│   ├── index.blade.php             # Course listing
│   └── show.blade.php              # Course details
├── dashboard/
│   ├── student.blade.php           # Student dashboard
│   ├── instructor.blade.php        # Instructor dashboard
│   └── admin.blade.php             # Admin dashboard
└── auth/                           # Authentication views

routes/
├── web.php                         # Web routes
└── auth.php                        # Authentication routes
```

## 🗄️ Database Schema

### Tables
- `users` - User accounts with roles
- `categories` - Course categories (Programming, Data Science, Design, etc.)
- `courses` - Course details, instructor, status, rating
- `lessons` - Individual lessons with content type and duration
- `enrollments` - Student-course enrollments with progress tracking
- Plus: `cache`, `jobs`, `sessions` for Laravel infrastructure

### Sample Data
- 1 Admin user: `admin@example.com`
- 1 Instructor user: `instructor@example.com`
- 3 Student users (auto-generated)
- 6 Course categories
- 1 Sample course with 5 lessons

## 🚀 Deployment Guide

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ (for frontend assets)
- Git
- A hosting provider (Render, Laravel Forge, DigitalOcean, AWS, Heroku, etc.)

### Option 1: Deploy to Render (Recommended for PaaS)

1. **Create a Render account** at https://render.com

2. **Push to GitHub**:
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git push origin main
   ```

3. **Create a new Web Service on Render**:
   - Connect your GitHub repository
   - Set runtime to `PHP 8.2`
   - Set build command: `composer install && npm install && npm run build`
   - Set start command: `php artisan migrate --force && php-server start`
   - Add environment variables (from `.env.example`)

4. **Configure environment**:
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Generate `APP_KEY`: Run locally `php artisan key:generate` and copy
   - Set database (PostgreSQL on Render)
   - Set `APP_URL` to your Render domain

5. **Database**:
   - Use Render's PostgreSQL addon
   - Update `.env` with PostgreSQL connection details
   - Render will auto-migrate on deploy

### Option 2: Deploy to Laravel Forge

1. Create a Laravel Forge account and server
2. Create a new site on your Forge server
3. Connect your GitHub repository
4. Configure deployment script:
   ```bash
   cd /home/forge/liveschool.com
   git pull origin main
   composer install --no-interaction --prefer-dist --optimize-autoloader
   npm ci && npm run build
   php artisan migrate --force
   php artisan view:clear
   php artisan config:clear
   ```
5. Set up SSL with Let's Encrypt (Forge does this automatically)

### Option 3: Deploy to DigitalOcean App Platform

1. Create a DigitalOcean account
2. Create an App Platform app
3. Connect GitHub repository
4. Configure build and run commands
5. Add PostgreSQL database
6. Deploy

### Option 4: Traditional VPS Deployment

1. **SSH into your server**
2. **Install dependencies**:
   ```bash
   apt-get update
   apt-get install -y php8.2-cli php8.2-fpm php8.2-pgsql php8.2-xml nginx composer
   ```
3. **Clone the repository**:
   ```bash
   cd /var/www
   git clone <your-repo-url> liveschool
   cd liveschool
   ```
4. **Install PHP dependencies**:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
5. **Install frontend dependencies**:
   ```bash
   npm ci
   npm run build
   ```
6. **Configure environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan config:cache
   ```
7. **Setup database**:
   ```bash
   php artisan migrate --force
   php artisan db:seed
   ```
8. **Configure Nginx**:
   ```nginx
   server {
       listen 80;
       server_name yourdomain.com;
       root /var/www/liveschool/public;

       add_header X-Frame-Options "SAMEORIGIN" always;
       add_header X-Content-Type-Options "nosniff" always;
       add_header X-XSS-Protection "1; mode=block" always;

       index index.php;

       charset utf-8;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location = /favicon.ico { access_log off; log_not_found off; }
       location = /robots.txt  { access_log off; log_not_found off; }

       error_page 404 /index.php;

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```
9. **Enable SSL**:
   ```bash
   certbot certonly --nginx -d yourdomain.com
   ```

## 🔐 Environment Variables

Key variables needed for production:

```env
APP_NAME="Live School"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...generated...
APP_URL=https://yourdomain.com

DB_CONNECTION=pgsql
DB_HOST=your-database-host
DB_PORT=5432
DB_DATABASE=liveschool_db
DB_USERNAME=user
DB_PASSWORD=password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Live School"
```

## 📱 Local Development Setup

1. **Clone the repository**:
   ```bash
   git clone <your-repo-url>
   cd OnlineSchoolPlatform
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install frontend dependencies**:
   ```bash
   npm install
   ```

4. **Create environment file**:
   ```bash
   cp .env.example .env
   ```

5. **Generate app key**:
   ```bash
   php artisan key:generate
   ```

6. **Run migrations and seed data**:
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Start development server**:
   ```bash
   npm run dev  # In one terminal
   php artisan serve  # In another terminal
   ```

8. **Access the application**:
   - URL: http://localhost:8000
   - Admin: admin@example.com / password
   - Instructor: instructor@example.com / password
   - Student: Any generated student account / password

## 🔄 Available Commands

```bash
# Development
php artisan serve              # Start development server
npm run dev                    # Start Vite dev server
npm run build                  # Build for production

# Database
php artisan migrate            # Run migrations
php artisan migrate:fresh      # Reset and migrate
php artisan migrate:fresh --seed  # Reset, migrate, and seed
php artisan db:seed            # Run seeders

# Code Quality
php artisan lint               # Check PHP syntax
composer test                  # Run tests (when added)

# Cache
php artisan config:cache       # Cache configuration
php artisan view:clear         # Clear view cache
php artisan cache:clear        # Clear all cache
```

## 📈 Performance Optimization

For production, ensure:

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache views
php artisan view:cache
```

## 🔍 Monitoring & Maintenance

- Set up error logging with services like Sentry
- Monitor database performance
- Review server logs regularly
- Keep Laravel and packages updated
- Backup database regularly
- Monitor storage usage for course materials

## 📝 Next Phase Features

Planned features for Phase 2:

- [ ] Instructor course creation UI
- [ ] Lesson editing and content management
- [ ] Video upload and streaming
- [ ] Quiz and assessment system
- [ ] Certificate generation
- [ ] Discussion forums per course
- [ ] Student comments and Q&A
- [ ] Notifications system
- [ ] Email notifications
- [ ] Live class scheduling
- [ ] Student peer-to-peer chat
- [ ] Advanced analytics dashboard
- [ ] SEO optimization
- [ ] Mobile app (React Native)
- [ ] API for integrations

## 📞 Support

For issues or questions about deployment, refer to:
- [Laravel Documentation](https://laravel.com/docs)
- [Render Deployment Guide](https://render.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)

---

**Last Updated**: May 2, 2026  
**Version**: 1.0.0 (Beta)
