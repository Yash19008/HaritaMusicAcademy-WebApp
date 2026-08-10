<?php

/**
 * Email Configuration Test Script
 * 
 * This script verifies SMTP configuration and sends a test email.
 * Run: php test-email.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "   📧 Harita Music Academy - Email Configuration Test\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

// Display configuration
echo "🔧 SMTP Configuration:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  Mailer:      " . config('mail.default') . "\n";
echo "  Host:        " . config('mail.mailers.smtp.host') . "\n";
echo "  Port:        " . config('mail.mailers.smtp.port') . "\n";
echo "  Username:    " . config('mail.mailers.smtp.username') . "\n";
echo "  Password:    " . (config('mail.mailers.smtp.password') ? '✓ Set (' . strlen(config('mail.mailers.smtp.password')) . ' chars)' : '✗ Not Set') . "\n";
echo "  Encryption:  " . (config('mail.mailers.smtp.encryption') ?: 'None') . "\n";
echo "  From:        " . config('mail.from.address') . "\n";
echo "  From Name:   " . config('mail.from.name') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";

// Test email send
echo "📤 Sending Test Email...\n";
echo "\n";

try {
    $testEmail = config('mail.from.address');
    
    Mail::raw(
        "🎵 Harita Music Academy - SMTP Test Email\n\n" .
        "This is a test email to verify your SMTP configuration.\n\n" .
        "Configuration Details:\n" .
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
        "Host:       " . config('mail.mailers.smtp.host') . "\n" .
        "Port:       " . config('mail.mailers.smtp.port') . "\n" .
        "Encryption: " . config('mail.mailers.smtp.encryption') . "\n" .
        "Username:   " . config('mail.mailers.smtp.username') . "\n" .
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n" .
        "✅ If you received this email, your SMTP is working correctly!\n\n" .
        "Next Steps:\n" .
        "1. Convert a test student from the Sales Dashboard\n" .
        "2. Check that the welcome email is sent\n" .
        "3. Verify login credentials in the email work\n\n" .
        "Sent at: " . now()->format('Y-m-d H:i:s') . "\n",
        function($message) use ($testEmail) {
            $message->to($testEmail)
                    ->subject('✅ Test Email - Harita Music Academy SMTP Setup');
        }
    );
    
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "   ✅ SUCCESS! Test email sent successfully!\n";
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "\n";
    echo "📬 Check your inbox at: " . $testEmail . "\n";
    echo "\n";
    echo "📋 Next Steps:\n";
    echo "  1. Check your email inbox (may take a few seconds)\n";
    echo "  2. If not in inbox, check spam/junk folder\n";
    echo "  3. Once confirmed, test student conversion:\n";
    echo "     → Go to Sales Dashboard\n";
    echo "     → Click 'Convert to Student' on any lead\n";
    echo "     → Fill in form with a test email\n";
    echo "     → Check that welcome email is received\n";
    echo "\n";
    echo "✨ Your email system is now working correctly!\n";
    echo "\n";
    
} catch (Exception $e) {
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "   ❌ ERROR: Failed to send test email\n";
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "\n";
    echo "Error Message:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo $e->getMessage() . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\n";
    
    // Provide specific troubleshooting based on error
    if (strpos($e->getMessage(), 'authentication failed') !== false) {
        echo "🔍 Issue: SMTP Authentication Failed\n";
        echo "\n";
        echo "This error means your username/password is incorrect or the\n";
        echo "email account doesn't exist in Hostinger.\n";
        echo "\n";
        echo "✅ Solutions:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "1. Login to Hostinger: https://hpanel.hostinger.com/\n";
        echo "2. Go to: Email → Email Accounts\n";
        echo "3. Check if '" . config('mail.mailers.smtp.username') . "' exists\n";
        echo "4. If NOT: Create the email account\n";
        echo "5. If YES: Reset/verify the password\n";
        echo "6. Update .env file with correct password\n";
        echo "7. Run: php artisan config:clear\n";
        echo "8. Test again: php test-email.php\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "\n";
        echo "📚 Detailed Help: Read EMAIL_TROUBLESHOOTING.md\n";
        echo "⚡ Quick Guide: Read QUICK_FIX_GUIDE.md\n";
        
    } elseif (strpos($e->getMessage(), 'Connection') !== false) {
        echo "🔍 Issue: Connection Failed\n";
        echo "\n";
        echo "✅ Solutions:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "1. Check your internet connection\n";
        echo "2. Verify Hostinger SMTP is not blocked by firewall\n";
        echo "3. Try alternative port/encryption:\n";
        echo "   Port 587 with TLS (current)\n";
        echo "   Port 465 with SSL (alternative)\n";
        echo "4. Check Hostinger status page\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
    } else {
        echo "📚 For help, read:\n";
        echo "  → EMAIL_TROUBLESHOOTING.md (detailed troubleshooting)\n";
        echo "  → QUICK_FIX_GUIDE.md (quick solutions)\n";
        echo "  → EMAIL_SETUP_GUIDE.md (complete setup guide)\n";
    }
    
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";
