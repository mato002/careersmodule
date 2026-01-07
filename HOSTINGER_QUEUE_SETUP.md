# Queue Worker Setup for Hostinger

## Understanding Hostinger Hosting

Hostinger offers different hosting types:
1. **Shared Hosting** - Limited server access, uses cPanel
2. **VPS Hosting** - Full server access, can install Supervisor
3. **Cloud Hosting** - Similar to VPS

## Option 1: Shared Hosting (cPanel) - Using Cron Jobs

If you have **shared hosting**, you'll use **cron jobs** to run the queue worker periodically.

### Step 1: Access cPanel Cron Jobs

1. Log into your Hostinger **hPanel** (or cPanel)
2. Go to **Advanced** → **Cron Jobs**

### Step 2: Create Cron Job

**Method A: Run queue worker every minute (Recommended)**

```
* * * * * cd /home/username/public_html/your-app && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

**Method B: Run queue worker continuously (Better for production)**

Create a cron job that runs every 5 minutes and processes all pending jobs:

```
*/5 * * * * cd /home/username/public_html/your-app && php artisan queue:work --stop-when-empty --tries=3 --timeout=120 >> /dev/null 2>&1
```

**Replace:**
- `/home/username/public_html/your-app` with your actual path
- Find your path in cPanel → File Manager

### Step 3: Find Your Application Path

In cPanel:
1. Go to **File Manager**
2. Navigate to your Laravel app folder
3. Check the path shown (usually `/home/username/public_html/` or `/home/username/domains/yourdomain.com/public_html/`)

### Step 4: Find PHP Path

In cPanel:
1. Go to **Select PHP Version** or **MultiPHP Manager**
2. Note the PHP version path (usually `/usr/bin/php` or `/opt/alt/php81/usr/bin/php`)

### Complete Cron Command Example

```
*/5 * * * * cd /home/u123456789/public_html && /usr/bin/php artisan queue:work --stop-when-empty --tries=3 --timeout=120 >> /home/u123456789/public_html/storage/logs/queue.log 2>&1
```

**This will:**
- Run every 5 minutes
- Process all queued jobs
- Stop when queue is empty
- Log output to `storage/logs/queue.log`

## Option 2: VPS Hosting - Using Supervisor (Recommended)

If you have **VPS hosting**, you can use Supervisor for continuous processing.

### Step 1: SSH into Your Server

```bash
ssh username@your-server-ip
```

### Step 2: Install Supervisor

```bash
sudo apt-get update
sudo apt-get install supervisor
```

### Step 3: Create Supervisor Config

Create file: `/etc/supervisor/conf.d/laravel-queue.conf`

```bash
sudo nano /etc/supervisor/conf.d/laravel-queue.conf
```

Add this content:

```ini
[program:laravel-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /home/username/public_html/artisan queue:work --sleep=3 --tries=3 --timeout=120
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/home/username/public_html/storage/logs/queue.log
stopwaitsecs=3600
```

**Replace:**
- `/home/username/public_html` with your actual path
- `www-data` with your web server user (check with `ps aux | grep php`)

### Step 4: Start Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-queue:*
```

### Step 5: Check Status

```bash
sudo supervisorctl status
```

You should see:
```
laravel-queue:laravel-queue_00   RUNNING   pid 12345, uptime 0:00:05
```

## Option 3: Alternative - Use Queue:listen with Screen/Tmux

If you can't use Supervisor, you can use `screen` or `tmux`:

### Using Screen

```bash
# Install screen (if not installed)
sudo apt-get install screen

# Start screen session
screen -S laravel-queue

# Run queue worker
cd /home/username/public_html
php artisan queue:work

# Detach: Press Ctrl+A, then D
# Reattach: screen -r laravel-queue
```

### Using Tmux

```bash
# Install tmux (if not installed)
sudo apt-get install tmux

# Start tmux session
tmux new -s laravel-queue

# Run queue worker
cd /home/username/public_html
php artisan queue:work

# Detach: Press Ctrl+B, then D
# Reattach: tmux attach -t laravel-queue
```

## Recommended Setup for Hostinger

### For Shared Hosting:
✅ **Use Cron Jobs** (Option 1)
- Run every 5 minutes
- Processes all pending jobs
- Simple and reliable

### For VPS/Cloud Hosting:
✅ **Use Supervisor** (Option 2)
- Continuous processing
- Auto-restart on failure
- Better performance

## Testing Your Setup

### 1. Check if Queue is Processing

```bash
# View queue logs
tail -f storage/logs/queue.log

# Or in cPanel File Manager
# Navigate to storage/logs/queue.log
```

### 2. Test with a Job

Submit a test job application with CV and check:
- Queue logs show processing
- Jobs table gets cleared
- Results appear in database

### 3. Monitor Failed Jobs

```bash
php artisan queue:failed
```

## Important Notes for Hostinger

1. **File Permissions**: Make sure `storage/logs/` is writable:
   ```bash
   chmod -R 775 storage/logs
   ```

2. **PHP Path**: Use full path to PHP in cron jobs:
   - Check with: `which php` (in SSH)
   - Or use: `/usr/bin/php` (common on Hostinger)

3. **Application Path**: Use full absolute path in cron jobs

4. **Logs Location**: Store logs in `storage/logs/` directory

5. **Memory Limits**: If jobs fail, increase PHP memory:
   ```bash
   php -d memory_limit=512M artisan queue:work
   ```

## Troubleshooting

### Jobs Not Processing

1. **Check cron is running:**
   ```bash
   # In cPanel, check cron job logs
   # Or add email notification to cron
   ```

2. **Check queue connection:**
   ```bash
   php artisan tinker
   # Then: config('queue.default')
   ```

3. **Check database connection:**
   ```bash
   php artisan migrate:status
   ```

### Supervisor Not Working

1. **Check Supervisor status:**
   ```bash
   sudo supervisorctl status
   ```

2. **Check Supervisor logs:**
   ```bash
   sudo tail -f /var/log/supervisor/supervisord.log
   ```

3. **Restart Supervisor:**
   ```bash
   sudo supervisorctl restart laravel-queue:*
   ```

## Quick Reference

### Cron Job (Every 5 minutes)
```
*/5 * * * * cd /path/to/app && /usr/bin/php artisan queue:work --stop-when-empty --tries=3
```

### Supervisor Command
```bash
php /path/to/app/artisan queue:work --sleep=3 --tries=3 --timeout=120
```

### Check Queue Status
```bash
php artisan queue:work --once
```

### View Failed Jobs
```bash
php artisan queue:failed
```

---

**Need Help?** Contact Hostinger support or check their documentation for specific server configurations.

