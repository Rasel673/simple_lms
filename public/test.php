<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<!DOCTYPE html><html><head><title>cPanel Server Diagnostics</title>";
echo "<style>body{font-family:sans-serif;padding:30px;background:#0f172a;color:#f8fafc;} .box{background:#1e293b;padding:24px;border-radius:12px;max-width:650px;margin:auto;} .ok{color:#4ade80;font-weight:bold;} .fail{color:#f87171;font-weight:bold;}</style></head><body><div class='box'>";
echo "<h2>🔍 cPanel Server Diagnostic Status</h2><hr style='border-color:#334155;margin-bottom:20px;'>";

// 1. PHP Version
$phpVersion = PHP_VERSION;
echo "<p><strong>PHP Version:</strong> $phpVersion ";
if (version_compare($phpVersion, '8.2.0', '>=')) {
    echo "<span class='ok'>✅ (Compatible: PHP 8.2+)</span></p>";
} else {
    echo "<span class='fail'>❌ (Incompatible: Laravel 12 requires PHP 8.2 or 8.3! Please change PHP version in cPanel MultiPHP Manager)</span></p>";
}

// 2. Vendor check
$vendorPath = __DIR__ . '/../vendor/autoload.php';
echo "<p><strong>Composer Vendor:</strong> ";
if (file_exists($vendorPath)) {
    echo "<span class='ok'>✅ vendor/autoload.php found</span></p>";
} else {
    echo "<span class='fail'>❌ Missing! vendor.zip has NOT been extracted into the project folder yet.</span></p>";
}

// 3. .env check
$envPath = __DIR__ . '/../.env';
echo "<p><strong>.env File:</strong> ";
if (file_exists($envPath)) {
    echo "<span class='ok'>✅ .env file exists</span></p>";
} else {
    echo "<span class='fail'>❌ Missing! Please rename .env.example to .env</span></p>";
}

// 4. Permissions check
$storagePath = __DIR__ . '/../storage';
$cachePath = __DIR__ . '/../bootstrap/cache';
echo "<p><strong>Storage Writable:</strong> " . (is_writable($storagePath) ? "<span class='ok'>✅ Writable</span>" : "<span class='fail'>❌ Not writable (Need chmod 775 or 755)</span>") . "</p>";
echo "<p><strong>Bootstrap Cache Writable:</strong> " . (is_writable($cachePath) ? "<span class='ok'>✅ Writable</span>" : "<span class='fail'>❌ Not writable</span>") . "</p>";

// 5. Extensions
echo "<h3>Required PHP Extensions:</h3><ul>";
$extensions = ['pdo_mysql', 'mbstring', 'openssl', 'fileinfo', 'curl', 'xml', 'bcmath', 'tokenizer'];
foreach ($extensions as $ext) {
    echo "<li>$ext: " . (extension_loaded($ext) ? "<span class='ok'>✅ Enabled</span>" : "<span class='fail'>❌ Disabled (Enable in cPanel 'Select PHP Version' -> Extensions)</span>") . "</li>";
}
echo "</ul>";

echo "</div></body></html>";
