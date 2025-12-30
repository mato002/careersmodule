# Career Module - Standalone Recruitment Platform

A comprehensive career and recruitment management system built with Laravel 12. This module provides a complete solution for companies to advertise job openings, manage applications, conduct aptitude tests and interviews, and track candidates through the hiring process. The stack uses Laravel 12 + Vite/Tailwind for a modern, responsive interface with an authenticated admin area for managing all recruitment activities.

## Features

### Public Website
- **Responsive Design**: Fully responsive public site with modern UI/UX
- **Home Page**: Dynamic homepage with customizable content sections
- **About Page**: Company information, team members, and CEO message
- **Products**: Dynamic product catalogue with images, descriptions, and CTAs (optional)
- **Careers**: Job posting system with application form and interview scheduling
- **Contact**: Contact form with honeypot protection and email notifications
- **FAQ**: Frequently asked questions management
- **Blog/News**: Content management system for posts and news
- **Newsletter**: Email subscription system integrated in footer
- **Cookie Consent**: GDPR-compliant cookie consent management

### Admin Dashboard
- **Admin Panel**: Role-gated admin access with comprehensive dashboard
- **Product Management**: Full CRUD for products with image galleries (optional)
- **Contact Management**: Review, reply, and track contact messages
- **Job Posting & Applications**: Manage job posts, applications, interviews, and reviews
- **Content Management**: Manage FAQs, blog posts, CEO messages, and team members
- **Branch Management**: Manage company branch locations
- **Settings**: Configure home, about, contact, logo, and API settings
- **Activity Logs**: Comprehensive activity logging system
- **Session Management**: Track and manage user sessions with device information
- **Profile Management**: User profile with active session monitoring and revocation

## Prerequisites

- PHP 8.2+
- Composer 2
- Node 18+ / npm 9+
- MySQL 8 (or another database supported by Laravel)
- A configured mailer (Mailgun, SMTP, etc.) for contact notifications

## Getting Started

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run dev   # or npm run build for production assets
php artisan serve
```

The seeder creates the initial admin user using the `ADMIN_NAME`, `ADMIN_EMAIL` and `ADMIN_PASSWORD` values from `.env`. Update those values before running `php artisan migrate --seed`, then log in via `/login` and you will be redirected to `/admin`.

## Environment Variables

| Key | Description |
| --- | --- |
| `APP_URL` | Base URL used in emails and asset generation |
| `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD` | Credentials for the seeded admin account |
| `CONTACT_NOTIFICATION_RECIPIENTS` | Comma-delimited list of addresses that should receive a copy of every contact submission |
| `MAIL_*` | Standard Laravel mail transport variables |

## Managing Products & Images

- Upload product images from the admin create/edit screens.
- Use the image management tools on the edit page to delete assets, update display order, or set the primary thumbnail.
- Uploaded files are stored on the `public` disk (`storage/app/public`). Remember to sync or back up `storage/` in production.

## Key Features

### Contact Form Flow
1. Visitor submits the contact form (throttled + honeypot protected).
2. Message is stored in the `contact_messages` table with status `new`.
3. Team recipients configured in `CONTACT_NOTIFICATION_RECIPIENTS` get an email containing all details.
4. The sender receives an acknowledgement email.
5. Admin staff can change the status to `in_progress` or `handled`, add notes, and mark the time the request was resolved.
6. Admin can send replies via email, SMS, or WhatsApp.

### Job Application & Interview Management
1. Job posts published on careers page with detailed descriptions.
2. Applicants submit applications with resumes and detailed information.
3. Admin can review, shortlist, schedule interviews, and track status.
4. Interview scheduling system with result tracking.
5. Bulk email confirmation functionality.
6. Status workflow: pending → reviewed → shortlisted → interview → hired/rejected.

### Session Management & Security
- **Active Session Tracking**: Monitor all active user sessions across devices
- **Session Limit**: Maximum 2 concurrent sessions per user (configurable)
- **Device Information**: Track browser, platform, IP address, and device type
- **Session Revocation**: Users can revoke individual or all other sessions from profile
- **Automatic Cleanup**: Scheduled daily cleanup of expired sessions via `sessions:cleanup` command
- **Activity Logging**: Comprehensive logging of user actions and system events

### Newsletter Subscription
- Email subscription form in website footer
- Duplicate prevention and resubscription handling
- Unsubscribe functionality
- Rate-limited subscription requests (5 per minute)

### Content Management
- **Home Settings**: Customize homepage content sections
- **About Settings**: Manage about page content
- **Contact Settings**: Configure contact page information
- **Team Members**: Manage team member profiles with photos
- **FAQs**: Dynamic FAQ management
- **Blog Posts**: Full CMS for news/blog posts with slugs
- **CEO Message**: Manage CEO message content
- **Branches**: Manage branch locations and information

## Artisan Commands

- `sessions:cleanup` - Clean up expired user sessions (scheduled to run daily at 2 AM)

## Testing

```bash
php artisan test
```

Feature coverage includes:

- Contact form persistence and mail notifications
- Admin middleware/authorization
- Basic product management scenarios
- User authentication and session management
- Profile management

## Deployment Checklist

- Set all environment variables (database, mailer, admin credentials, notification recipients).
- Run `php artisan migrate --seed`.
- Run `php artisan storage:link`.
- Compile production assets: `npm run build`.
- Configure a queue worker if you decide to queue outgoing mail in the future.
- Set up cron job for scheduled tasks:
  ```bash
  * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
  ```
- Create an automated backup for the database and `storage/app/public`.
- Configure session driver (default: database) in `.env`.
- Ensure proper file permissions for `storage/` and `bootstrap/cache/` directories.

## License

Proprietary — Fortress Lenders Ltd. All rights reserved.