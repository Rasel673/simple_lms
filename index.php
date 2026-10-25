<?php

/**
 * Laravel Root Front Controller for Shared cPanel Hosting
 * Seamlessly delegates requests to the public/ directory
 */

define('LARAVEL_START', microtime(true));

// Forward to Laravel's real front controller
require __DIR__ . '/public/index.php';
