<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Api\VapiWebhookController;

$payload = [
    'message' => [
        'type' => 'tool-calls',
        'toolCalls' => [
            [
                'id' => 'test_id',
                'function' => [
                    'name' => 'save_ticket',
                    'arguments' => [
                        'assistance_type' => 'Test',
                        'company_name' => 'Test Co',
                        'customer_name' => 'Test User',
                        'description' => 'Test issue'
                    ]
                ]
            ]
        ]
    ]
];

$request = Request::create('/api/vapi/webhook', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));
$controller = new VapiWebhookController();
$response = $controller->handle($request);

echo "Response status: " . $response->getStatusCode() . "\n";
echo "Response body: " . $response->getContent() . "\n";
