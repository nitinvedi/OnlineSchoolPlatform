# Online School Platform

A comprehensive learning management system built with Laravel 12, featuring courses, quizzes, live sessions, certificates, and payment integration.

## Features

- 🎓 **Course Management**: Create and manage courses with lessons and quizzes
- 👥 **User Roles**: Student, Instructor, and Admin roles with proper permissions
- 💳 **Payment Integration**: Stripe-powered course purchases
- 📹 **Live Sessions**: Real-time video sessions using Jitsi Meet
- 📜 **Certificates**: Auto-generated PDF certificates upon course completion
- 📊 **Dashboard**: Role-based dashboards for all user types
- 📧 **Email Notifications**: Automated emails for enrollments and certificates
- 🎨 **Modern UI**: Tailwind CSS with Alpine.js for interactive components

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade templates, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Payments**: Stripe
- **Video**: Jitsi Meet
- **PDF**: DomPDF
- **Build**: Vite

## Local Development

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL
- Git

### Setup
1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install Node dependencies:
   ```bash
   npm install
   ```
4. Copy environment file:
   ```bash
   cp .env.example .env
   ```
5. Generate app key:
   ```bash
   php artisan key:generate
   ```
6. Set up database in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=online_school
   DB_USERNAME=root
   DB_PASSWORD=
   ```
7. Run migrations:
   ```bash
   php artisan migrate --seed
   ```
8. Build assets:
   ```bash
   npm run build
   ```
9. Start the server:
   ```bash
   php artisan serve
   ```

Visit `http://127.0.0.1:8000` to access the application.

## Deployment

### Render (Recommended)

1. **Push to GitHub**: Ensure your code is in a GitHub repository

2. **Create Render Account**: Sign up at [render.com](https://render.com)

3. **Connect Repository**:
   - New → Web Service
   - Select your GitHub repo
   - Branch: `main`

4. **Service Configuration**:
   - **Language**: PHP
   - **Build Command**:
     ```bash
     composer install --no-dev --optimize-autoloader && npm install && npm run build
     ```
   - **Start Command**:
     ```bash
     php artisan serve --host 0.0.0.0 --port $PORT
     ```
   - **Publish Directory**: `public`

5. **Create MySQL Database**:
   - New → Database → MySQL
   - Copy the connection details

6. **Environment Variables**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-service.onrender.com

   DB_CONNECTION=mysql
   DB_HOST=<render-mysql-host>
   DB_PORT=<render-mysql-port>
   DB_DATABASE=<render-mysql-database>
   DB_USERNAME=<render-mysql-user>
   DB_PASSWORD=<render-mysql-password>

   FILESYSTEM_DISK=public
   MAIL_MAILER=log

   # Add these for payments
   STRIPE_PUBLIC_KEY=pk_live_...
   STRIPE_SECRET_KEY=sk_live_...
   STRIPE_WEBHOOK_SECRET=whsec_...
   ```

7. **Post-Deployment Setup**:
   - Open Render shell and run:
     ```bash
     php artisan migrate --force
     php artisan db:seed --force
     php artisan storage:link
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     ```

### Alternative: Infrastructure as Code

Use the included `render.yaml` file for automated deployment:

1. Push `render.yaml` to your repo
2. In Render: New → Blueprint
3. Select your repo
4. Render will create services automatically

## Default Users

After seeding, you can log in with:

- **Admin**: `admin@example.com` / `password`
- **Instructor**: `instructor@example.com` / `password`
- **Students**: Generated users with role `student`

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `php artisan test`
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
