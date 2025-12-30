# Sample User Credentials

This document contains the login credentials for all sample users created by the UserSeeder.

## How to Seed Users

Run the following command to create all sample users:

```bash
php artisan db:seed --class=UserSeeder
```

Or run all seeders:

```bash
php artisan db:seed
```

## User Credentials

### Administrator Accounts

#### Admin User
- **Email:** `admin@example.com`
- **Password:** `password123`
- **Role:** Administrator
- **Access:** Full system access

#### Super Admin
- **Email:** `superadmin@example.com`
- **Password:** `password123`
- **Role:** Administrator
- **Access:** Full system access

### HR Manager Accounts

#### HR Manager
- **Email:** `hr@example.com`
- **Password:** `password123`
- **Role:** HR Manager
- **Access:** Career module, job posts, applications, aptitude tests, interviews

#### Sarah Johnson (HR Manager)
- **Email:** `sarah.hr@example.com`
- **Password:** `password123`
- **Role:** HR Manager
- **Access:** Career module, job posts, applications, aptitude tests, interviews

### Content Editor

#### Content Editor
- **Email:** `editor@example.com`
- **Password:** `password123`
- **Role:** Editor
- **Access:** Content management permissions

### Regular Users

#### John Doe
- **Email:** `user@example.com`
- **Password:** `password123`
- **Role:** User
- **Access:** Basic user access

### Candidate

#### Jane Candidate
- **Email:** `candidate@example.com`
- **Password:** `password123`
- **Role:** Candidate
- **Access:** Can view and apply for jobs, track applications

## Default Admin User

The system also creates a default admin user from environment variables:

- **Email:** From `ADMIN_EMAIL` env variable (default: `admin@fortresslenders.com`)
- **Password:** From `ADMIN_PASSWORD` env variable (default: `ChangeMe123!`)
- **Name:** From `ADMIN_NAME` env variable (default: `Fortress Admin`)

## Security Note

⚠️ **Important:** These are sample credentials for development/testing purposes only. 

**DO NOT** use these credentials in production environments. Always change default passwords and use strong, unique passwords for production systems.

## Quick Login Links

After seeding, you can access the login page at:
- **Admin/HR/Editor/User Login:** `/login`
- **Public Careers Page:** `/` or `/careers`

