# Quick Start Guide - BPA Asset Management System

## Prerequisites

Before you begin, make sure you have:
- PHP 8.2 or higher installed
- Composer installed
- MySQL or MariaDB installed and running
- A web browser

## Installation Steps

### For Windows (XAMPP/WAMP)

1. **Copy the project** to your web server directory:
   - For XAMPP: `C:\xampp\htdocs\BPA-Asset-Management`
   - For WAMP: `C:\wamp64\www\BPA-Asset-Management`

2. **Open Command Prompt** and navigate to the project:
   ```cmd
   cd C:\xampp\htdocs\BPA-Asset-Management
   ```

3. **Install dependencies**:
   ```cmd
   composer install
   ```

4. **Create environment file**:
   ```cmd
   copy .env.example .env
   ```

5. **Configure your database** in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bpa_asset_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Create the database** using phpMyAdmin:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Click "New" to create a database
   - Name it: `bpa_asset_management`
   - Select collation: `utf8mb4_unicode_ci`

7. **Generate application key**:
   ```cmd
   php artisan key:generate
   ```

8. **Run migrations and seeders**:
   ```cmd
   php artisan migrate
   php artisan db:seed
   ```

9. **Create storage link**:
   ```cmd
   php artisan storage:link
   ```

10. **Start the server**:
    ```cmd
    php artisan serve
    ```

11. **Open your browser** and go to: `http://localhost:8000`

### For Linux (Ubuntu/Debian)

1. **Navigate to your web directory**:
   ```bash
   cd /var/www/html
   ```

2. **Clone the project**:
   ```bash
   sudo git clone <repository-url> BPA-Asset-Management
   ```

3. **Install dependencies**:
   ```bash
   cd BPA-Asset-Management
   composer install
   ```

4. **Set permissions**:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/BPA-Asset-Management
   sudo chmod -R 775 /var/www/html/BPA-Asset-Management/storage
   sudo chmod -R 775 /var/www/html/BPA-Asset-Management/bootstrap/cache
   ```

5. **Create environment file**:
   ```bash
   cp .env.example .env
   ```

6. **Configure database** in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bpa_asset_management
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

7. **Create database**:
   ```bash
   sudo mysql -u root -p
   CREATE DATABASE bpa_asset_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   EXIT;
   ```

8. **Generate key and run setup**:
   ```bash
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   php artisan storage:link
   ```

9. **Start server**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

10. **Access the application** at: `http://your-server-ip:8000`

## For DirectAdmin/cPanel Hosting

### Step 1: Upload Files
- Use FileZilla or similar FTP client
- Upload all files to your `public_html` or subdomain directory

### Step 2: Database Setup
- Log in to DirectAdmin
- Create MySQL database and user
- Note down the credentials

### Step 3: Configure .env
- Edit the `.env` file with your database credentials
- Update `APP_URL` to your domain

### Step 4: SSH Access (if available)
Connect via SSH and run:
```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Permissions
```bash
chmod -R 775 storage bootstrap/cache
chmod -R 755 public
```

## Default Login Credentials

After running seeders, use these credentials:

- **Email**: admin@bpa.com
- **Password**: password

⚠️ **IMPORTANT**: Change the admin password immediately after first login!

## Troubleshooting

### Database Connection Error
- Check `.env` database credentials
- Ensure MySQL service is running
- Verify database exists

### Permission Denied Errors
```bash
chmod -R 775 storage bootstrap/cache
```

### Storage Link Error
```bash
php artisan storage:link
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## First Time Setup Checklist

- [ ] Change admin password
- [ ] Update company information in Settings
- [ ] Upload company logo in Settings
- [ ] Add department information
- [ ] Add category information
- [ ] Add staff users
- [ ] Start adding assets

## Getting Help

If you encounter issues:
1. Check the `storage/logs/laravel.log` file
2. Run `php artisan config:clear`
3. Verify all requirements are met
4. Contact the development team

## Features Overview

### Dashboard
View quick stats and recent activity on the main dashboard.

### Assets
- Add new assets with unique asset tags
- Assign assets to users/departments
- Track asset history and movements
- Upload asset images

### Categories
Organize assets by type (IT Equipment, Vehicles, Furniture, etc.)

### Departments
Manage departments and track asset distribution.

### Users
- Admin users have full access
- Staff users have limited access
- Track assigned assets per user

### Reports
Generate and export reports:
- Assets by department
- Assets by status
- Asset value summary
- Activity logs

### Settings
- Configure company information
- Upload company logo
- Set timezone and email settings

## Next Steps

1. **Configure Email**: Update SMTP settings in `.env` for production
2. **Set up Cron**: For scheduled tasks (optional)
3. **Regular Backups**: Backup database and storage regularly
4. **Monitor Logs**: Check logs for errors and security issues

## Security Recommendations

1. Change default admin credentials
2. Use strong passwords
3. Enable HTTPS
4. Regular backups
5. Keep system updated
6. Monitor access logs
7. Set proper file permissions
8. Use environment variables for secrets

## Support

For technical support, please contact:
- Email: support@bpa.com
- Phone: +63 2 123 4567

---

**Built with ❤️ for BPA (Broadcasting & Publications Authority)**
