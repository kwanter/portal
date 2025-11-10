# Application Portal

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

**A centralized directory portal for managing and displaying organizational applications**

</div>

---

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Technology Stack](#technology-stack)
- [Requirements](#requirements)
- [Quick Start](#quick-start)
- [Detailed Installation](#detailed-installation)
- [Apache Configuration](#apache-configuration)
- [Usage](#usage)
- [Database Schema](#database-schema)
- [Testing](#testing)
- [Security Features](#security-features)
- [Audit Trail](#audit-trail)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

**Application Portal** is a Laravel-based web application that serves as a centralized directory for managing and listing all applications used by an organization. The portal organizes applications into two main categories:

- **Kesekretariatan** (Secretariat)
- **Kepaniteraan** (Registry)

The application provides both a **public-facing front page** where anyone can browse applications without authentication, and a **secure admin dashboard** for authenticated users to perform full CRUD operations.

---

## Key Features

### Public Access
- **Public Application Directory** - View all applications without login
- **Category Organization** - Browse by Kesekretariatan or Kepaniteraan
- **Search & Filter** - Search by name, URL, or description; filter by category
- **Responsive Design** - Mobile, tablet, and desktop support with dark mode

### Admin Features
- **Full CRUD Operations** - Create, Read, Update, Delete applications
- **User Management** - Manage system users with role-based access
- **Soft Deletes** - Applications can be deleted and restored
- **Complete Audit Trail** - Track all changes with full history
- **User Tracking** - Automatic tracking of who created/updated/deleted records
- **Dashboard Analytics** - View statistics and recent activities

### Technical Features
- **UUID Primary Keys** - All tables use UUID instead of auto-incrementing integers
- **Database Integrity** - Foreign key constraints and proper indexing
- **Comprehensive Testing** - 11 feature tests covering all functionality
- **CSRF Protection** - All forms include CSRF tokens
- **XSS Protection** - Blade template engine auto-escapes output
- **Password Hashing** - Bcrypt hashing for secure passwords

---

## Technology Stack

| Category | Technology |
|----------|-----------|
| **Backend Framework** | Laravel 12.x |
| **PHP Version** | 8.2+ |
| **Database** | MySQL 8.0+ (SQLite supported) |
| **Authentication** | Laravel Breeze |
| **Frontend** | Blade Templates |
| **CSS Framework** | Tailwind CSS 3.x |
| **JavaScript** | Alpine.js |
| **Build Tool** | Vite 7.x |
| **Audit Logging** | owen-it/laravel-auditing v14.0 |
| **Testing** | PHPUnit 11.x |
| **Web Server** | Apache / Nginx |

---

## Requirements

Before installing, ensure your system meets these requirements:

- **PHP** 8.2 or higher
- **Composer** (latest version)
- **MySQL** 8.0 or higher (or compatible database)
- **Apache** or **Nginx** web server
- **Node.js** 18+ and **NPM** (for asset compilation)
- **Git** (for version control)

### PHP Extensions Required
- PDO PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- BCMath PHP Extension

---

## Quick Start

Get up and running in 5 minutes:

```bash
# 1. Navigate to project directory
cd /Users/macbook/Developer/php/portal

# 2. Install dependencies
composer install
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Create and configure database
mysql -u root -p -e "CREATE DATABASE portal;"
# Update .env with your database credentials

# 5. Run migrations and seeders
php artisan migrate
php artisan db:seed

# 6. Build assets
npm run build

# 7. Start development server
php artisan serve
```

Now visit `http://localhost:8000` to see the public portal, or login at `http://localhost:8000/login` with the default credentials below.

---

## Detailed Installation

### Step 1: Install PHP Dependencies

```bash
composer install
```

This will install all Laravel dependencies including:
- Laravel Framework 12.x
- Laravel Breeze (authentication)
- Laravel Auditing (audit trail)
- PHPUnit (testing)

### Step 2: Install Node Dependencies

```bash
npm install
```

This will install:
- Vite (build tool)
- Tailwind CSS (styling)
- Alpine.js (JavaScript framework)
- PostCSS (CSS processing)

### Step 3: Configure Environment

1. **Copy environment file**:
   ```bash
   cp .env.example .env
   ```

2. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

3. **Update database credentials** in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portal
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Configure application URL** (optional):
   ```env
   APP_URL=http://portal.local
   ```

### Step 4: Create MySQL Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Step 5: Run Migrations

```bash
php artisan migrate
```

This will create the following tables:
- `users` - User accounts
- `applications` - Application directory
- `audits` - Audit trail
- `cache`, `jobs`, `sessions` - Laravel system tables

### Step 6: Seed Database

```bash
php artisan db:seed
```

This will create:
- 3 default users (admin, kesekretariatan, kepaniteraan)
- Sample applications for each category

### Step 7: Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### Step 8: Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

If using Apache:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## Apache Configuration

### Option 1: Using the Included Configuration File

1. **Copy Apache configuration**:
   ```bash
   sudo cp apache-portal.conf /etc/apache2/sites-available/portal.conf
   ```

2. **Edit the configuration**:
   ```bash
   sudo nano /etc/apache2/sites-available/portal.conf
   ```
   
   Update these paths to match your system:
   ```apache
   DocumentRoot /your/path/to/portal/public
   <Directory /your/path/to/portal/public>
   ```

3. **Enable the site**:
   ```bash
   sudo a2ensite portal.conf
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

4. **Add to hosts file** (for local development):
   ```bash
   sudo nano /etc/hosts
   ```
   Add:
   ```
   127.0.0.1  portal.local
   ```

### Option 2: Manual Configuration

Create a new virtual host file:

```bash
sudo nano /etc/apache2/sites-available/portal.conf
```

Add this configuration:

```apache
<VirtualHost *:80>
    ServerName portal.local
    DocumentRoot /path/to/portal/public

    <Directory /path/to/portal/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/portal-error.log
    CustomLog ${APACHE_LOG_DIR}/portal-access.log combined
</VirtualHost>
```

---

## Usage

### Default Credentials

After seeding the database, you can login with these accounts:

| Role | Email | Password | Purpose |
|------|-------|----------|---------|
| **Admin** | admin@portal.com | password | Full system access |
| **Kesekretariatan** | kesekretariatan@portal.com | password | Secretariat user |
| **Kepaniteraan** | kepaniteraan@portal.com | password | Registry user |

**IMPORTANT:** Change these passwords in production!

### Public Portal

- **URL**: `http://localhost:8000` (or your configured domain)
- **Access**: No login required
- **Features**:
  - View all active applications
  - Search by name, URL, or description
  - Filter by category (Kesekretariatan/Kepaniteraan)
  - Responsive grid layout with dark mode support

### Admin Dashboard

- **URL**: `http://localhost:8000/login`
- **Access**: Requires authentication
- **Features**:
  - Dashboard with statistics
  - Create new applications
  - Edit existing applications
  - Delete applications (soft delete)
  - Restore deleted applications
  - View audit trail for each application
  - Manage users
  - Search and filter capabilities

### Managing Applications

#### Create New Application
1. Login to admin dashboard
2. Navigate to "Applications"
3. Click "Add New Application"
4. Fill in required fields:
   - **Name**: Application name (unique)
   - **URL**: Application URL (must be valid URL format)
   - **Description**: Brief description
   - **Category**: Kesekretariatan or Kepaniteraan
5. Click "Save"

#### Edit Application
1. Click "Edit" button on application card
2. Modify fields as needed
3. Click "Update"
4. View audit trail on detail page to see changes

#### Delete Application
1. Click "Delete" button on application card
2. Confirm deletion
3. Application is soft-deleted (can be restored)

#### Restore Deleted Application
1. View trash/deleted applications
2. Click "Restore" button
3. Application is restored with full history

### User Management

Administrators can manage users:
- Create new user accounts
- Update user information
- Delete users (soft delete)
- Reset user passwords

---

## Database Schema

### Users Table

| Column | Type | Description |
|--------|------|-------------|
| `id` | UUID | Primary key |
| `name` | VARCHAR(255) | User's full name |
| `email` | VARCHAR(255) | Unique email address |
| `password` | VARCHAR(255) | Bcrypt hashed password |
| `remember_token` | VARCHAR(100) | Remember me token |
| `email_verified_at` | TIMESTAMP | Email verification timestamp |
| `created_at` | TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | Last update timestamp |
| `deleted_at` | TIMESTAMP | Soft delete timestamp |

**Indexes**: Primary key on `id`, unique index on `email`

---

### Applications Table

| Column | Type | Description |
|--------|------|-------------|
| `id` | UUID | Primary key |
| `name` | VARCHAR(255) | Application name (unique) |
| `url` | VARCHAR(500) | Application URL |
| `description` | TEXT | Application description |
| `category` | ENUM | 'kesekretariatan' or 'kepaniteraan' |
| `created_by` | UUID | FK to users.id |
| `updated_by` | UUID | FK to users.id (nullable) |
| `deleted_by` | UUID | FK to users.id (nullable) |
| `created_at` | TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | Last update timestamp |
| `deleted_at` | TIMESTAMP | Soft delete timestamp |

**Indexes**: 
- Primary key on `id`
- Index on `category`
- Index on `name`
- Index on `deleted_at`

**Foreign Keys**:
- `created_by` → `users.id` (ON DELETE RESTRICT)
- `updated_by` → `users.id` (ON DELETE SET NULL)
- `deleted_by` → `users.id` (ON DELETE SET NULL)

---

### Audits Table

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT | Auto-increment primary key |
| `user_type` | VARCHAR(255) | User model class |
| `user_id` | UUID | User who performed action |
| `event` | VARCHAR(255) | Action type (created/updated/deleted) |
| `auditable_type` | VARCHAR(255) | Model being audited |
| `auditable_id` | UUID | ID of audited record |
| `old_values` | JSON | Before values |
| `new_values` | JSON | After values |
| `url` | TEXT | Request URL |
| `ip_address` | VARCHAR(45) | User's IP address |
| `user_agent` | TEXT | User's browser |
| `tags` | VARCHAR(255) | Custom tags |
| `created_at` | TIMESTAMP | Audit timestamp |
| `updated_at` | TIMESTAMP | Update timestamp |

**Indexes**: Composite indexes on `auditable_type` and `auditable_id`

---

## Testing

The application includes comprehensive feature tests covering all functionality.

### Run All Tests

```bash
php artisan test
```

### Run Specific Test File

```bash
php artisan test tests/Feature/ApplicationTest.php
```

### Run Specific Test Method

```bash
php artisan test --filter test_can_create_application
```

### Test Coverage

The test suite includes 11 tests covering:

- **Authentication**
  - Routes require authentication
  - Unauthenticated users are redirected
  
- **CRUD Operations**
  - Viewing applications index
  - Creating new applications
  - Updating existing applications
  - Deleting applications (soft delete)
  - Restoring deleted applications
  
- **Validation**
  - Required fields (name, URL, description, category)
  - URL format validation
  - Category enum validation
  - Unique name validation
  
- **Features**
  - Search functionality
  - Category filtering
  - User tracking (created_by, updated_by, deleted_by)
  - Audit trail creation

### Development Testing

For continuous testing during development:

```bash
composer dev
```

This runs tests in watch mode and reruns on file changes.

---

## Security Features

### Authentication & Authorization
- **Laravel Breeze** authentication system
- Session-based authentication
- Password reset functionality
- Email verification support
- Protected routes with middleware

### Data Security
- **CSRF Protection** on all forms
- **SQL Injection Prevention** via Eloquent ORM
- **XSS Protection** via Blade templating
- **Password Hashing** using Bcrypt
- **Mass Assignment Protection** with fillable/guarded properties

### Database Security
- **Foreign Key Constraints** for referential integrity
- **Soft Deletes** for data recovery
- **UUID Primary Keys** to prevent enumeration attacks
- **Database Transactions** for complex operations

### Additional Security
- **Rate Limiting** on login attempts
- **Secure Headers** configuration
- **HTTPS Support** (recommended for production)
- **Environment Variable Protection** (.env not in version control)

---

## Audit Trail

Every modification to applications is tracked with complete audit logging.

### What is Tracked?

- **User Information**: Who performed the action
- **Timestamp**: When the action occurred
- **Action Type**: Created, updated, or deleted
- **Before/After Values**: Complete change history
- **Request Information**: URL, IP address, user agent
- **Related Data**: Tags and custom metadata

### Viewing Audit Trail

1. Navigate to application detail page
2. Scroll to "Audit Trail" section
3. View complete history of changes
4. See who created, updated, or deleted the record

### Audit Events

| Event | Description |
|-------|-------------|
| `created` | New application created |
| `updated` | Application details modified |
| `deleted` | Application soft-deleted |
| `restored` | Deleted application restored |

### Audit Data Structure

```json
{
  "old_values": {
    "name": "Old Application Name",
    "url": "https://old-url.com"
  },
  "new_values": {
    "name": "New Application Name",
    "url": "https://new-url.com"
  }
}
```

---

## Deployment

### Production Checklist

Before deploying to production:

- [ ] Update `.env` with production database credentials
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Change all default passwords
- [ ] Configure proper `APP_URL`
- [ ] Set up SSL certificate (HTTPS)
- [ ] Configure email settings for notifications
- [ ] Set up regular database backups
- [ ] Configure proper file permissions
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build` for production assets
- [ ] Set up monitoring and logging

### Optimization Commands

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Optimize application
php artisan optimize
```

### Database Backup

Create regular backups:

```bash
# Manual backup
mysqldump -u root -p portal > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore from backup
mysql -u root -p portal < backup_20251110_120000.sql
```

### Environment Variables

Key production environment variables:

```env
APP_NAME="Application Portal"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://portal.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=portal
DB_USERNAME=portal_user
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

---

## Troubleshooting

### Common Issues and Solutions

#### MySQL Connection Failed

**Problem**: Can't connect to database

**Solutions**:
1. Check MySQL service is running:
   ```bash
   sudo systemctl status mysql
   ```

2. Verify database exists:
   ```bash
   mysql -u root -p
   SHOW DATABASES;
   ```

3. Check `.env` credentials match database user

4. Test connection:
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

---

#### Permission Denied Errors

**Problem**: Can't write to storage or cache directories

**Solutions**:
1. Set correct permissions:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

2. Set correct ownership:
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   ```

3. Verify Apache user:
   ```bash
   ps aux | grep apache
   ```

---

#### 404 Not Found on Routes

**Problem**: Routes return 404 errors

**Solutions**:
1. Enable Apache mod_rewrite:
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

2. Check `.htaccess` exists in `public/` directory

3. Verify Apache virtual host `AllowOverride All`

4. Clear route cache:
   ```bash
   php artisan route:clear
   ```

---

#### Assets Not Loading

**Problem**: CSS/JS files not found

**Solutions**:
1. Rebuild assets:
   ```bash
   npm run build
   ```

2. Check `public/build` directory exists

3. Verify Vite configuration in `vite.config.js`

4. Clear cache:
   ```bash
   php artisan view:clear
   ```

---

#### Migration Errors

**Problem**: Migrations fail to run

**Solutions**:
1. Reset and re-run migrations:
   ```bash
   php artisan migrate:fresh
   ```

2. Check database character set (should be utf8mb4):
   ```sql
   SHOW VARIABLES LIKE 'character_set_database';
   ```

3. Verify MySQL version is 8.0+:
   ```bash
   mysql --version
   ```

---

#### Composer/NPM Issues

**Problem**: Dependencies won't install

**Solutions**:
1. Clear Composer cache:
   ```bash
   composer clear-cache
   composer install
   ```

2. Clear NPM cache:
   ```bash
   npm cache clean --force
   rm -rf node_modules package-lock.json
   npm install
   ```

---

### Maintenance Commands

Clear all caches:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Check application status:
```bash
php artisan about
```

View logs:
```bash
tail -f storage/logs/laravel.log
```

Apache logs:
```bash
tail -f /var/log/apache2/error.log
```

---

## Contributing

We welcome contributions! Here's how to get started:

### Development Workflow

1. **Fork the repository**
2. **Create a feature branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes**:
   - Follow PSR-12 coding standards
   - Write clear commit messages
   - Add/update tests as needed

4. **Run tests**:
   ```bash
   php artisan test
   ```

5. **Submit a pull request**:
   - Describe your changes
   - Reference any related issues
   - Ensure all tests pass

### Code Standards

- Follow **PSR-12** coding style
- Use **meaningful variable names**
- Add **PHPDoc comments** for methods
- Keep **methods focused** and small
- Write **tests** for new features
- Update **documentation** as needed

### Testing Requirements

- All new features must include tests
- Maintain or improve test coverage
- Tests must pass before merging
- Include both positive and negative test cases

---

## License

This project is proprietary software. All rights reserved.

---

## Support

For questions, issues, or support:

- **Email**: support@yourdomain.com
- **Documentation**: See `SETUP_GUIDE.md` and `PROJECT_SUMMARY.md`
- **Issue Tracker**: Create an issue in the repository

---

## Changelog

### Version 1.0.0 (2025-11-05)

**Initial Release**

- Laravel 12.x application framework
- Public application directory portal
- Admin dashboard with full CRUD operations
- UUID implementation for all tables
- Soft delete support with restore functionality
- Complete audit trail with owen-it/laravel-auditing
- User tracking (created_by, updated_by, deleted_by)
- Category-based organization (Kesekretariatan/Kepaniteraan)
- Search and filter capabilities
- Laravel Breeze authentication
- Responsive UI with Tailwind CSS and Alpine.js
- Dark mode support
- Comprehensive test coverage (11 feature tests)
- Database migrations and seeders
- Apache configuration included
- Complete documentation

---

<div align="center">

**Built with Laravel** | **Powered by PHP** | **Styled with Tailwind CSS**

</div>
