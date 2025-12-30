# What Happens When You Ban a User or Block an IP

## 🔴 Blocking an IP Address

### When You Block an IP:
1. **Record Created**: A new record is added to the `blocked_ips` table with:
   - The IP address
   - Reason (optional, defaults to "Blocked from activity log")
   - Who blocked it (your admin user ID)
   - Reference to the activity log that triggered it
   - Expiration date (optional - can be permanent or temporary)
   - Status: Active

2. **Activity Logged**: The blocking action is recorded in activity logs

3. **Immediate Effect**: 
   - The IP is immediately blocked from accessing the website
   - All requests from that IP will receive a **403 Forbidden** error
   - The error message shows: "Your IP address has been blocked. Reason: [your reason]"

### What Happens to Blocked IPs:
- **On Every Request**: The `CheckBlockedIp` middleware checks if the IP is blocked
- **403 Error**: If blocked, the request is immediately rejected with a 403 error
- **Access Denied**: They cannot:
  - View any pages
  - Login
  - Submit forms
  - Access any part of the website

### Temporary vs Permanent Blocks:
- **Permanent**: If no expiration date is set, the block lasts until manually unblocked
- **Temporary**: If an expiration date is set, the block automatically expires at that time

### Unblocking an IP:
- Sets `is_active = false` in the database
- IP can immediately access the website again
- Action is logged in activity logs

---

## 👤 Banning a User

### When You Ban a User:
1. **User Record Updated**: The user's `is_banned` field is set to `true` in the `users` table

2. **All Sessions Revoked**: 
   - All active sessions for that user are immediately deleted from the `sessions` table
   - User is logged out from all devices instantly

3. **Activity Logged**: The ban action is recorded in activity logs with user details

4. **Immediate Effect**:
   - If the user is currently logged in, they are immediately logged out
   - They cannot log back in
   - They see: "Your account has been banned. Please contact the administrator."

### What Happens to Banned Users:

#### During Login Attempt:
- **Before Authentication**: System checks if user is banned before attempting login
- **Login Blocked**: If banned, login fails with error message
- **Cannot Authenticate**: Even with correct password, they cannot log in

#### If Already Logged In:
- **Middleware Check**: `CheckBannedUser` middleware runs on every request
- **Immediate Logout**: User is automatically logged out
- **Session Destroyed**: Their session is invalidated and token regenerated
- **Redirected**: They are redirected to login page with error message

#### What They Cannot Do:
- ❌ Login to the system
- ❌ Access admin panel
- ❌ View any protected pages
- ❌ Submit any forms
- ❌ Access their account

### Unbanning a User:
- Sets `is_banned = false` in the database
- User can immediately log in again (with correct credentials)
- Action is logged in activity logs

---

## 🔍 Where These Checks Happen

### IP Blocking Checks:
1. **CheckBlockedIp Middleware**: Runs on EVERY request (web middleware group)
2. **Location**: `app/Http/Middleware/CheckBlockedIp.php`
3. **When**: Before any controller logic runs
4. **Result**: 403 error if IP is blocked

### User Banning Checks:
1. **CheckBannedUser Middleware**: Runs on EVERY authenticated request
2. **LoginRequest**: Checks before authentication attempt
3. **Location**: 
   - `app/Http/Middleware/CheckBannedUser.php`
   - `app/Http/Requests/Auth/LoginRequest.php`
4. **When**: 
   - On every request (if user is logged in)
   - During login attempt
5. **Result**: Logout + redirect to login with error message

---

## 📊 Database Changes

### When Blocking IP:
```sql
INSERT INTO blocked_ips (
    ip_address,
    reason,
    blocked_by,
    activity_log_id,
    expires_at,
    is_active,
    blocked_at
) VALUES (...)
```

### When Banning User:
```sql
UPDATE users 
SET is_banned = TRUE 
WHERE id = [user_id];

DELETE FROM sessions 
WHERE user_id = [user_id];
```

---

## 🛡️ Security Features

1. **Automatic Enforcement**: No manual intervention needed - blocks/bans are enforced automatically
2. **Session Revocation**: Banned users lose all active sessions immediately
3. **Preventive Checks**: Checks happen before authentication, preventing login attempts
4. **Activity Logging**: All actions are logged for audit purposes
5. **Temporary Blocks**: IP blocks can have expiration dates for temporary restrictions

---

## ⚠️ Important Notes

- **IP Blocks**: Affect ALL users from that IP address (shared networks)
- **User Bans**: Only affect the specific user account
- **Reversibility**: Both actions can be reversed (unblock/unban)
- **Logging**: All actions are tracked in activity logs for accountability
- **Immediate Effect**: Changes take effect immediately, no cache clearing needed

