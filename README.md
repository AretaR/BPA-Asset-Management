# BPA Asset Management System

A comprehensive, production-ready asset management system built with Laravel for BPA (Broadcasting & Publications Authority).

## Features

- **Asset Management**: Full CRUD operations for assets with tracking and history
- **Category Management**: Organize assets by categories
- **Department Management**: Manage departments and asset allocation
- **User Management**: Role-based access control (Super Admin / Admin / Staff)
- **RBAC (Roles & Permissions)**: Granular access control with management UI
- **Check-in/Check-out**: Asset assignment and movement tracking
- **Activity Logs**: Complete audit trail of all system activities
- **Reports**: Export reports to PDF and Excel
- **Responsive UI**: Modern, clean interface with Bootstrap 5

## Requirements

- PHP 8.2 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web Server (Apache/Nginx)

## Installation

### 1. Clone or Download the Project

```bash
git clone <repository-url> BPA-Asset-Management
cd BPA-Asset-Management
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Edit the `.env` file and configure your database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bpa_asset_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Create Database

Create a MySQL database:

```sql
CREATE DATABASE bpa_asset_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Run Migrations and Seeders

```bash
php artisan migrate
php artisan db:seed
```

### 7. Set Permissions (Linux)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

> ⚠️ `php artisan storage:link` is no longer required — the symlink is created automatically at boot, and the MediaController serves files via the `media/{path}` route instead.

## Default Login Credentials

After seeding, you can login with:

| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | super_admin@bpa.com | password |
| **Admin** | admin@bpa.com | password |
| **Staff** | staff@bpa.com | password |

⚠️ **Change passwords immediately after first login!**

## For Production Deployment (DirectAdmin/cPanel)

### 1. Upload Files

Upload all files to your `public_html` or subdomain directory.

### 2. Configure Database

Create a MySQL database in DirectAdmin/cPanel and update `.env` accordingly.

### 3. Set Document Root

In DirectAdmin, set the document root to the `public` folder.

### 4. Run Commands

SSH into your server and run:

```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
chmod -R 755 public
```

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Application controllers
│   │   └── Middleware/      # Role & Permission middleware
│   ├── Models/              # Eloquent models (incl. Role, Permission)
│   ├── Policies/            # Authorization policies
│   ├── Exports/             # Excel export classes
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/             # Database seeders
│   └── factories/           # Model factories
├── resources/views/
│   ├── roles/               # RBAC role management views
│   ├── permissions/         # RBAC permission management views
│   └── ...                  # Other Blade templates
├── routes/                  # Application routes
├── public/                  # Public assets
└── storage/                 # Storage files
```

## Key Routes

| Route | Description | Access |
|-------|-------------|--------|
| `/dashboard` | Main dashboard | Authenticated users |
| `/assets` | Asset management | Authenticated users |
| `/categories` | Category management | Admin+ |
| `/departments` | Department management | Admin+ |
| `/users` | User management | Admin+ |
| `/roles` | Role management | Super Admin only |
| `/permissions` | Permission management | Super Admin only |
| `/reports` | Generate reports | Authenticated users |
| `/settings` | System settings | Admin+ |
| `/media/{path}` | Serve uploaded files | Public |

## Asset Statuses

- **Available**: Asset is ready to be assigned
- **Assigned**: Asset is currently assigned to a user
- **Maintenance**: Asset is under repair/maintenance
- **Retired**: Asset has been retired/decommissioned

## User Roles

| Role | Level | Description |
|------|-------|-------------|
| **Super Admin** | 3 | Full system access, bypasses all authorization, can manage roles & permissions |
| **Admin** | 2 | Full access to features except RBAC management |
| **Staff** | 1 | View assets and limited management capabilities |

## Reports Available

1. **Assets by Department**: View asset distribution across departments
2. **Assets by Status**: View asset count by status
3. **Asset Value Summary**: Total value of all assets
4. **Activity Logs**: System audit trail

## Export Formats

All reports can be exported to:
- **PDF**: For printing and sharing
- **Excel**: For further analysis and manipulation

## Security Features

- CSRF Protection
- Role-based Access Control (3-tier hierarchy)
- Granular Permissions (manageable via UI)
- Form Validation
- Soft Deletes
- Activity Logging
- Gate-based Super Admin bypass

## Useful Commands

```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Regenerate autoload
composer dump-autoload

# Create new migration
php artisan make:migration create_assets_table

# Create new seeder
php artisan make:seeder UserSeeder

# Run specific seeder
php artisan db:seed --class=RoleAndPermissionSeeder

# Fresh database with seeding
php artisan migrate:fresh --seed
```

## Troubleshooting

### Common Issues

1. **Permission denied on storage**: Ensure proper permissions are set
2. **Database connection error**: Check `.env` database configuration
3. **404 on media files**: Files may not exist on the storage disk — avatars fall back to SVG initials, asset images return null gracefully

### Logs

Check Laravel logs at `storage/logs/laravel.log` for error details.

## Support

For issues and feature requests, please contact the development team.

## License

This project is proprietary software for BPA (Broadcasting & Publications Authority).

## Credits

Built with Laravel, Bootstrap 5, and FontAwesome.
