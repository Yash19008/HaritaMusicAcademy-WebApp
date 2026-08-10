# Test Email Configuration

## ✅ SMTP Settings Updated!

Your `.env` file has been updated with Hostinger SMTP credentials:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=hello@codespine.in
MAIL_PASSWORD=YOUR_ACTUAL_PASSWORD_HERE  ⚠️ REPLACE THIS!
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@codespine.in"
MAIL_FROM_NAME="Harita Music Academy"
```

---

## ⚠️ IMPORTANT: Update Password

**You MUST replace `YOUR_ACTUAL_PASSWORD_HERE` with your actual email password!**

1. Open `.env` file
2. Find the line: `MAIL_PASSWORD=YOUR_ACTUAL_PASSWORD_HERE`
3. Replace with your real password: `MAIL_PASSWORD=your_real_password`
4. Save the file
5. Run: `php artisan config:clear`

---

## Test Email Sending

### Method 1: Using Tinker (Quick Test)

```bash
php artisan tinker
```

Then in Tinker console:
```php
\Mail::raw('This is a test email from Harita Music Academy', function($message) {
    $message->to('your-test-email@gmail.com')
            ->subject('Test Email - Harita Music Academy');
});
```

Press `Ctrl+C` to exit Tinker.

**Check your inbox!** If you receive the email, SMTP is working! ✅

---

### Method 2: Create a Test Student (Full Test)

1. Go to **Sales Dashboard**
2. Click **"Convert to Student"** on any lead
3. Fill in the form with a **real test email** (your email)
4. Submit the form
5. **Check your inbox** for the welcome email with login credentials

---

## Troubleshooting

### If email doesn't send:

#### 1. Check Password
```bash
# View current mail config
php artisan tinker
>>> config('mail.mailers.smtp.password')
```

If it shows `YOUR_ACTUAL_PASSWORD_HERE`, you need to update it!

#### 2. Check SMTP Settings
```bash
php artisan tinker
>>> config('mail.mailers.smtp.host')
# Should show: smtp.hostinger.com

>>> config('mail.mailers.smtp.port')
# Should show: 587

>>> config('mail.mailers.smtp.username')
# Should show: hello@codespine.in
```

#### 3. Check Hostinger Email Settings

**Hostinger SMTP Settings (Verify these):**
- **Outgoing Server (SMTP):** smtp.hostinger.com
- **Port:** 587 (TLS) or 465 (SSL)
- **SMTP Authentication:** YES
- **Username:** hello@codespine.in (full email address)
- **Password:** Your email password
- **Encryption:** TLS

#### 4. Test with Telnet

```bash
telnet smtp.hostinger.com 587
# If connection successful, SMTP server is reachable
```

#### 5. Check Laravel Logs

```bash
tail -f storage/logs/laravel.log
```

Look for error messages related to email sending.

#### 6. Common Hostinger Issues

**Issue:** Authentication failed
**Solution:** 
- Make sure you're using the **full email address** as username
- Verify password is correct
- Check if 2FA is enabled (may need app-specific password)

**Issue:** Connection timeout
**Solution:**
- Check firewall isn't blocking port 587
- Try port 465 with SSL encryption instead

**Issue:** Email marked as spam
**Solution:**
- Set up SPF and DKIM records in Hostinger DNS
- Use proper `MAIL_FROM_NAME` and `MAIL_FROM_ADDRESS`

---

## Alternative: Use Hostinger Port 465 (SSL)

If port 587 doesn't work, try port 465 with SSL:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=hello@codespine.in
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="hello@codespine.in"
MAIL_FROM_NAME="Harita Music Academy"
```

Then run:
```bash
php artisan config:clear
```

---

## Verify Configuration

Run these commands to verify settings are loaded:

```bash
php artisan tinker
```

```php
// Check SMTP host
config('mail.mailers.smtp.host')
// Should return: "smtp.hostinger.com"

// Check port
config('mail.mailers.smtp.port')
// Should return: 587

// Check username
config('mail.mailers.smtp.username')
// Should return: "hello@codespine.in"

// Check from address
config('mail.from.address')
// Should return: "hello@codespine.in"

// Check from name
config('mail.from.name')
// Should return: "Harita Music Academy"

// Exit
exit
```

---

## Quick Test Command

Create a test file `test-email.php` in your project root:

```php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    Mail::raw('Test email from Harita Music Academy!', function($message) {
        $message->to('your-email@example.com')
                ->subject('Test Email');
    });
    echo "✅ Email sent successfully!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
```

Run:
```bash
php test-email.php
```

---

## Success Checklist

- [ ] Updated `MAIL_PASSWORD` in `.env` file with real password
- [ ] Ran `php artisan config:clear`
- [ ] Ran `php artisan cache:clear`
- [ ] Tested with Tinker
- [ ] Received test email in inbox
- [ ] Email not in spam folder
- [ ] Login credentials email working

---

## Next Steps

Once email is working:

1. ✅ Convert a test student
2. ✅ Check email inbox for welcome email
3. ✅ Verify login credentials in email
4. ✅ Test student login with credentials
5. ✅ Confirm student can access dashboard

---

## Current Status

✅ SMTP configuration updated in `.env`
✅ Config cache cleared
⚠️ **ACTION REQUIRED:** Update `MAIL_PASSWORD` with real password
🔄 **NEXT:** Test email sending

---

## Need Help?

**Hostinger Support:** https://www.hostinger.com/tutorials/how-to-use-free-email
**Laravel Mail Docs:** https://laravel.com/docs/11.x/mail

If you continue to have issues, check:
1. Email password is correct
2. Email account exists in Hostinger
3. SMTP is enabled for the email account
4. No firewall blocking port 587
5. PHP mail functions are not disabled
