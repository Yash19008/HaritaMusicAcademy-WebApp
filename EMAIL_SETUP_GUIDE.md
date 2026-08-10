# Email Setup Guide - Harita Music Academy

## Current Configuration

Your `.env` file currently has:
```env
MAIL_MAILER=log
```

This means emails are **logged to file** (`storage/logs/laravel.log`) instead of being sent. This is fine for development/testing.

---

## Email Service Options

### Option 1: Gmail SMTP (Free, Easiest for Testing)

**Steps:**

1. **Enable 2-Factor Authentication** in your Gmail account
2. **Generate App Password:**
   - Go to: https://myaccount.google.com/apppasswords
   - Select "Mail" and "Other (Custom name)"
   - Enter "Harita Music Academy"
   - Copy the 16-character password

3. **Update `.env` file:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Harita Music Academy"
```

**Pros:** 
- ✅ Free
- ✅ Easy to set up
- ✅ Reliable

**Cons:**
- ❌ Limited to 500 emails/day
- ❌ Not suitable for production
- ❌ Can be flagged as spam

---

### Option 2: Mailtrap (Best for Development/Testing)

**What is Mailtrap?**
A fake SMTP server that captures emails for testing (emails don't actually send to real users).

**Steps:**

1. **Sign up:** https://mailtrap.io (Free account)
2. **Get credentials** from your inbox
3. **Update `.env` file:**

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@haritamusicacademy.com
MAIL_FROM_NAME="Harita Music Academy"
```

**Pros:**
- ✅ Perfect for testing
- ✅ See exactly what emails look like
- ✅ Free tier available
- ✅ No risk of sending test emails to real users

**Cons:**
- ❌ Emails don't actually send (testing only)

---

### Option 3: SendGrid (Production Ready)

**Best for:** Production with high volume

**Steps:**

1. **Sign up:** https://sendgrid.com (Free: 100 emails/day)
2. **Create API Key:**
   - Settings → API Keys → Create API Key
   - Name: "Harita Music Academy"
   - Permissions: "Full Access"
   - Copy the API key

3. **Update `.env` file:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@haritamusicacademy.com
MAIL_FROM_NAME="Harita Music Academy"
```

**Pros:**
- ✅ Free tier: 100 emails/day
- ✅ Production-ready
- ✅ Good deliverability
- ✅ Email analytics
- ✅ Easy to scale

**Cons:**
- ❌ Requires domain verification for best results
- ❌ Paid plans for higher volume

---

### Option 4: Amazon SES (Cheapest for Production)

**Best for:** High volume, low cost

**Steps:**

1. **AWS Account:** Sign up at https://aws.amazon.com
2. **SES Setup:**
   - Go to Amazon SES
   - Verify email address or domain
   - Request production access (by default in sandbox)

3. **Get SMTP Credentials:**
   - SES Console → SMTP Settings
   - Create SMTP Credentials

4. **Update `.env` file:**

```env
MAIL_MAILER=smtp
MAIL_HOST=email-smtp.us-east-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=your-ses-smtp-username
MAIL_PASSWORD=your-ses-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@haritamusicacademy.com
MAIL_FROM_NAME="Harita Music Academy"
```

**Pros:**
- ✅ Extremely cheap ($0.10 per 1000 emails)
- ✅ Highly scalable
- ✅ Very reliable
- ✅ AWS infrastructure

**Cons:**
- ❌ More complex setup
- ❌ Requires AWS account
- ❌ Need to verify domain

---

### Option 5: Mailgun (Easy Production Setup)

**Best for:** Small to medium production apps

**Steps:**

1. **Sign up:** https://mailgun.com (Free: 5,000 emails/month for 3 months)
2. **Get credentials:**
   - Sending → Domains → Select domain
   - Copy SMTP credentials

3. **Update `.env` file:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@your-domain.mailgun.org
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@haritamusicacademy.com
MAIL_FROM_NAME="Harita Music Academy"
```

**Pros:**
- ✅ Easy setup
- ✅ Good free tier
- ✅ Excellent documentation
- ✅ Good deliverability

**Cons:**
- ❌ Requires domain verification
- ❌ Free tier limited to 3 months

---

## Recommended Setup by Environment

### Development/Testing:
```env
# Option 1: Log to file (current - no emails sent)
MAIL_MAILER=log

# Option 2: Mailtrap (see emails in inbox without sending)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
```

### Small Production (< 500 emails/day):
```env
# SendGrid Free Tier
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@haritamusicacademy.com
MAIL_FROM_NAME="Harita Music Academy"
```

### Large Production:
```env
# Amazon SES (most cost-effective)
MAIL_MAILER=smtp
MAIL_HOST=email-smtp.us-east-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=your-ses-smtp-username
MAIL_PASSWORD=your-ses-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@haritamusicacademy.com
MAIL_FROM_NAME="Harita Music Academy"
```

---

## Quick Start: Using Gmail for Testing

**Right now, for testing, use Gmail:**

1. Go to your Gmail account
2. Enable 2-Factor Authentication
3. Generate App Password: https://myaccount.google.com/apppasswords
4. Copy the 16-character password (e.g., `abcd efgh ijkl mnop`)

5. **Update your `.env` file:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=youremail@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=youremail@gmail.com
MAIL_FROM_NAME="Harita Music Academy"
```

6. **Clear config cache:**
```bash
php artisan config:clear
```

7. **Test email:**
```bash
php artisan tinker
>>> \Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

---

## Testing the Student Welcome Email

### Option 1: Keep `MAIL_MAILER=log` (Current)

Emails are logged to `storage/logs/laravel.log`

**Check the log:**
```bash
tail -f storage/logs/laravel.log
```

### Option 2: Use Mailtrap (Recommended for Development)

1. Sign up at https://mailtrap.io
2. Get credentials from your inbox
3. Update `.env` with Mailtrap credentials
4. Convert a student → Email appears in Mailtrap inbox
5. See exactly how the email looks

### Option 3: Use Real Gmail (For Real Testing)

1. Set up Gmail SMTP (see above)
2. Convert a student with your real email
3. Check your Gmail inbox
4. See the actual email

---

## Configuration File

The mail configuration is in `config/mail.php`. You don't need to change this file - just update your `.env` file.

**Current settings from `.env`:**
```php
'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
    'name' => env('MAIL_FROM_NAME', 'Harita Music Academy'),
],
```

---

## Troubleshooting

### Email not sending?

1. **Check `.env` file is updated**
2. **Clear config cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Test mail configuration:**
   ```bash
   php artisan tinker
   >>> config('mail.mailers.smtp.host')
   >>> config('mail.from.address')
   ```

### Gmail not working?

- ✅ 2FA must be enabled
- ✅ Use App Password (not regular password)
- ✅ Remove spaces from app password
- ✅ Check if "Less secure app access" is needed (older accounts)

### SendGrid not working?

- ✅ Verify sender email/domain
- ✅ Check API key is correct
- ✅ Username must be exactly `apikey`

---

## Production Checklist

Before going to production:

- [ ] Use production SMTP service (SendGrid/SES/Mailgun)
- [ ] Verify sender domain (SPF, DKIM, DMARC records)
- [ ] Test email deliverability
- [ ] Set up proper `MAIL_FROM_ADDRESS` (not Gmail)
- [ ] Add email to queue (for better performance)
- [ ] Set up email monitoring/alerts
- [ ] Add unsubscribe link (if sending marketing emails)
- [ ] Comply with email regulations (CAN-SPAM, GDPR)

---

## Queue Configuration (Optional - Better Performance)

For better performance, send emails via queue:

1. **Update `.env`:**
```env
QUEUE_CONNECTION=database
```

2. **Update mail send code:**
```php
// Instead of:
\Mail::to($user->email)->send(new StudentCreatedMail($user, $password));

// Use queue:
\Mail::to($user->email)->queue(new StudentCreatedMail($user, $password));
```

3. **Run queue worker:**
```bash
php artisan queue:work
```

---

## Summary

**For Right Now (Development):**
- Keep `MAIL_MAILER=log` → Emails logged to file ✅
- OR use Mailtrap → See emails in test inbox ✅
- OR use Gmail → Send real test emails ✅

**For Production:**
- SendGrid (easy, 100 free/day) ✅
- Amazon SES (cheapest, $0.10/1000) ✅
- Mailgun (good balance) ✅

**Current Status:**
- Your emails are being **logged** to `storage/logs/laravel.log`
- You can see the email content in the log file
- No actual emails are being sent to users
- This is **perfect for development**! ✅

To send real emails, just update the `.env` file with one of the SMTP configurations above! 📧
