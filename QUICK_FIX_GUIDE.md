# ⚡ Quick Fix Guide - Email Authentication Error

## 🚨 Current Problem
Email authentication is failing with error:
```
535 5.7.8 Error: authentication failed
```

---

## ✅ Quick Fix (5 Minutes)

### Step 1: Login to Hostinger
Go to: https://hpanel.hostinger.com/

### Step 2: Navigate to Email
Click: **Email** → **Email Accounts**

### Step 3: Check if `hello@codespine.in` Exists

#### If It DOESN'T Exist:
1. Click **"Create Email Account"**
2. Username: `hello`
3. Domain: Select `codespine.in`
4. Set a strong password → **SAVE THIS PASSWORD!**
5. Click Create

#### If It DOES Exist:
1. Click the **⋮** (three dots) next to the account
2. Click **"Change Password"**
3. Set a new password → **SAVE THIS PASSWORD!**

### Step 4: Update .env File
Open `.env` file and update this line:
```env
MAIL_PASSWORD=your_actual_password_from_hostinger
```

### Step 5: Test
```bash
php artisan config:clear
php verify-smtp.php
```

### Expected Result
```
✅ SUCCESS! Test email sent successfully!
📧 Check your inbox at: hello@codespine.in
```

---

## 🎯 That's It!

Once you see the success message, the email system will work for:
- ✅ Student welcome emails with login credentials
- ✅ Automatic email when converting leads/demos
- ✅ Future password resets and notifications

---

## 📚 More Help

Need detailed instructions?
- **Read**: `EMAIL_TROUBLESHOOTING.md` (comprehensive guide)
- **Read**: `SETUP_COMPLETE_SUMMARY.md` (full overview)

---

**Quick Question to Check:**
Can you login to https://webmail.hostinger.com/ with:
- Email: `hello@codespine.in`
- Password: `G#pjijJc51`

**If NO** → The password is wrong → Follow steps above to fix it!
**If YES** → Something else is wrong → Check EMAIL_TROUBLESHOOTING.md

---

Good luck! 🚀
