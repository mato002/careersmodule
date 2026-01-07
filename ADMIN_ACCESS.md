# How to Access Admin Dashboard

## Admin Dashboard URL

Since the Career Module is deployed in the `/careers` subdirectory, the admin dashboard is accessible at:

**URL:** `https://pradytec.com/careers/admin`

## Login URL

To log in as an admin, visit:

**URL:** `https://pradytec.com/careers/login`

## Access Requirements

1. **You need an admin account** - An admin user must be created in the database
2. **Login credentials** - Email and password for the admin account

## Creating Admin User

If you don't have an admin user yet, you can create one using one of these methods:

### Method 1: Using SQL Script (Recommended)

Run the SQL script `create_admin_user.sql` or `create_both_users.sql` in your database via cPanel phpMyAdmin or MySQL.

### Method 2: Using PHP Script

Upload and run `create_hr_manager.php` or `restore_admin_user.php` via browser (visit the file URL).

### Method 3: Via Seeder (if you have SSH access)

```bash
cd public_html/Careers
php artisan db:seed --class=UserSeeder
```

Make sure your `.env` has:
```env
ADMIN_NAME=Admin User
ADMIN_EMAIL=admin@pradytec.com
ADMIN_PASSWORD=your-secure-password
```

## Default Admin Credentials

Check your seeder files or SQL scripts to see what default credentials were set:
- `create_admin_user.sql`
- `create_both_users.sql`
- `database/seeders/UserSeeder.php`

## Quick Access Links

- **Login:** `https://pradytec.com/careers/login`
- **Admin Dashboard:** `https://pradytec.com/careers/admin`
- **Admin Profile:** `https://pradytec.com/careers/admin/profile`

## After Login

Once logged in as admin, you'll be automatically redirected to:
- `https://pradytec.com/careers/admin` (Admin Dashboard)

## Admin Features Available

From the admin dashboard, you can:
- Manage job posts
- Review job applications
- Manage users and permissions
- Configure settings (logo, API, general)
- View activity logs
- Manage tokens
- And more...

## Troubleshooting

### Can't Access Admin Dashboard

1. **Check if you're logged in:**
   - Visit `https://pradytec.com/careers/login`
   - Log in with admin credentials

2. **Check user role:**
   - Your user account must have role `admin` or `hr_manager`
   - Check in database: `users` table, `role` column

3. **Check middleware:**
   - Admin routes require authentication and admin role
   - Make sure you're logged in with the correct account

### Forgot Password

If you forgot the admin password, you can:
1. Reset it via database (update `users` table)
2. Use `update_admin_password.php` script
3. Or create a new admin user

## Security Note

After first login, consider:
1. Changing the default admin password
2. Creating additional admin/HR manager accounts
3. Reviewing user permissions
4. Setting up proper security measures





