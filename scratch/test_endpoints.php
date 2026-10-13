<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$routesToTest = [
    '/',
    '/courses',
    '/courses/complete-laravel-masterclass',
    '/about',
    '/contact',
    '/verify-certificate',
    '/privacy-policy',
    '/terms-conditions',
    '/sitemap.xml',
    '/login',
    '/register',
];

echo "Testing Public Endpoints...\n";
foreach ($routesToTest as $uri) {
    try {
        $request = Illuminate\Http\Request::create($uri, 'GET');
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        echo sprintf("[%d] %s\n", $status, $uri);
        if ($status !== 200) {
            echo "   Error: " . substr(strip_tags($response->getContent()), 0, 300) . "\n";
        }
    } catch (\Throwable $e) {
        echo sprintf("[EXCEPTION] %s: %s in %s:%d\n", $uri, $e->getMessage(), $e->getFile(), $e->getLine());
    }
}
