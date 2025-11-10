#!/bin/bash
# Portal Diagnostic Script
# Upload this to your server and run: sudo bash diagnose.sh

echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║         Portal.pn - Server Diagnostic Script                 ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""

echo "1️⃣  PHP VERSION:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
php -v | head -1
echo ""

echo "2️⃣  PHP-FPM STATUS:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if systemctl is-active --quiet php8.2-fpm; then
    echo "✅ PHP 8.2 FPM is RUNNING"
elif systemctl is-active --quiet php-fpm; then
    echo "✅ PHP-FPM is RUNNING"
else
    echo "❌ PHP-FPM is NOT RUNNING"
    echo "   Fix: sudo systemctl start php8.2-fpm"
fi
echo ""

echo "3️⃣  APACHE STATUS:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if systemctl is-active --quiet apache2; then
    echo "✅ Apache is RUNNING"
else
    echo "❌ Apache is NOT RUNNING"
    echo "   Fix: sudo systemctl start apache2"
fi
echo ""

echo "4️⃣  PHP-FPM SOCKETS:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if ls /run/php/*.sock >/dev/null 2>&1; then
    ls -lh /run/php/*.sock
else
    echo "❌ No PHP-FPM sockets found in /run/php/"
fi
echo ""

echo "5️⃣  APPLICATION DIRECTORY:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ -d "/var/www/portal" ]; then
    ls -ld /var/www/portal
    echo "Public directory:"
    ls -ld /var/www/portal/public
else
    echo "❌ /var/www/portal not found"
fi
echo ""

echo "6️⃣  STORAGE PERMISSIONS:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ -d "/var/www/portal/storage" ]; then
    ls -ld /var/www/portal/storage
    if [ -w "/var/www/portal/storage" ]; then
        echo "✅ Storage is writable"
    else
        echo "❌ Storage is NOT writable"
        echo "   Fix: sudo chmod -R 775 /var/www/portal/storage"
    fi
else
    echo "❌ Storage directory not found"
fi
echo ""

echo "7️⃣  ENVIRONMENT FILE:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ -f "/var/www/portal/.env" ]; then
    ls -la /var/www/portal/.env
    if grep -q "^APP_KEY=base64:" /var/www/portal/.env; then
        echo "✅ APP_KEY is set"
    else
        echo "❌ APP_KEY is not set"
        echo "   Fix: cd /var/www/portal && sudo -u www-data php artisan key:generate"
    fi
else
    echo "❌ .env file not found"
    echo "   Fix: Copy .env.example to .env"
fi
echo ""

echo "8️⃣  HTACCESS FILES:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ -f "/var/www/portal/public/.htaccess" ]; then
    echo "✅ /public/.htaccess exists ($(wc -l < /var/www/portal/public/.htaccess) lines)"
else
    echo "❌ /public/.htaccess NOT found"
fi
if [ -f "/var/www/portal/.htaccess" ]; then
    echo "⚠️  Root .htaccess exists (should be disabled)"
fi
echo ""

echo "9️⃣  APACHE MODULES:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
apache2ctl -M 2>/dev/null | grep -E "(rewrite|proxy_fcgi|headers)" || echo "Cannot check modules"
echo ""

echo "🔟 RECENT APACHE ERRORS (last 10 lines):"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ -f "/var/log/apache2/portal-error.log" ]; then
    tail -10 /var/log/apache2/portal-error.log
elif [ -f "/var/log/apache2/error.log" ]; then
    tail -10 /var/log/apache2/error.log | grep portal
else
    echo "No Apache error log found"
fi
echo ""

echo "1️⃣1️⃣  RECENT PHP-FPM ERRORS (last 10 lines):"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ -f "/var/log/php8.2-fpm.log" ]; then
    tail -10 /var/log/php8.2-fpm.log
elif [ -f "/var/log/php-fpm.log" ]; then
    tail -10 /var/log/php-fpm.log
else
    echo "No PHP-FPM log found"
fi
echo ""

echo "1️⃣2️⃣  VIRTUALHOST CONFIGURATION:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ -f "/etc/apache2/sites-available/portal.conf" ]; then
    echo "Portal VirtualHost config:"
    grep -E "(ServerName|DocumentRoot|SetHandler|proxy)" /etc/apache2/sites-available/portal.conf
elif [ -f "/etc/apache2/sites-enabled/portal.conf" ]; then
    echo "Portal VirtualHost config:"
    grep -E "(ServerName|DocumentRoot|SetHandler|proxy)" /etc/apache2/sites-enabled/portal.conf
else
    echo "Portal VirtualHost config not found"
fi
echo ""

echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║                       DIAGNOSTIC COMPLETE                     ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""
echo "💡 Common Fixes:"
echo "   - PHP-FPM not running: sudo systemctl start php8.2-fpm"
echo "   - Wrong permissions: sudo chown -R www-data:www-data /var/www/portal"
echo "   - Missing APP_KEY: cd /var/www/portal && sudo -u www-data php artisan key:generate"
echo "   - Clear cache: cd /var/www/portal && sudo -u www-data php artisan optimize:clear"
