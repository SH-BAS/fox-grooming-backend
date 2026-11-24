<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$routes = app('router')->getRoutes();

$folders = [];

foreach ($routes as $route) {
    if (!str_starts_with($route->uri(), 'api/')) {
        continue;
    }

    $uri = '{{base_url}}/' . $route->uri();
    $methods = $route->methods();
    // exclude HEAD
    $methods = array_filter($methods, fn($m) => $m !== 'HEAD');
    $method = array_values($methods)[0];

    $action = $route->getAction();
    $middlewares = $route->gatherMiddleware();
    $isAuth = in_array('auth:api', $middlewares);

    $parameters = [];
    $folderName = 'Miscellaneous';

    // Extract parameters from method source code
    if (isset($action['controller'])) {
        list($controllerClass, $methodName) = explode('@', $action['controller']);
        
        $controllerName = class_basename($controllerClass);
        $folderName = str_replace(['Controller', 'Api'], '', $controllerName);
        if ($folderName === 'Auth') $folderName = 'Authentication';
        if (empty($folderName)) $folderName = 'Miscellaneous';

        if (class_exists($controllerClass) && method_exists($controllerClass, $methodName)) {
            $reflector = new ReflectionMethod($controllerClass, $methodName);
            $filename = $reflector->getFileName();
            $start_line = $reflector->getStartLine() - 1;
            $end_line = $reflector->getEndLine();
            
            if ($filename && file_exists($filename)) {
                $source = file($filename);
                $methodSource = implode("", array_slice($source, $start_line, $end_line - $start_line));

                // Match Validator::make(..., [...]) or request()->validate([...])
                if (preg_match('/Validator::make[^\[]*\[(.*?)\]/s', $methodSource, $matches) || 
                    preg_match('/\$request->validate\(\s*\[(.*?)\]/s', $methodSource, $matches)) {
                    
                    $rulesBlock = $matches[1];
                    preg_match_all("/'([^']+)'\s*=>\s*'([^']+)'/", $rulesBlock, $rulesMatches, PREG_SET_ORDER);
                    
                    foreach ($rulesMatches as $ruleMatch) {
                        $paramName = $ruleMatch[1];
                        $rules = $ruleMatch[2];
                        
                        $type = 'text';
                        $value = '';
                        // Basic guessing
                        if (str_contains($rules, 'file') || str_contains($rules, 'image') || str_contains($rules, 'mimes')) {
                            $type = 'file';
                        } elseif (str_contains($rules, 'boolean')) {
                            $value = '1';
                        } elseif (str_contains($rules, 'email')) {
                            $value = 'test@example.com';
                        } elseif (str_contains($rules, 'password')) {
                            $value = 'password';
                        } elseif (str_contains($rules, 'date')) {
                            $value = '2026-03-28';
                        } elseif (str_contains($rules, 'integer') || str_contains($rules, 'numeric')) {
                            $value = '1';
                        } else {
                            $value = 'test_value';
                        }

                        $parameters[] = [
                            'key' => $paramName,
                            'value' => $value,
                            'type' => $type,
                            'description' => $rules
                        ];
                    }
                }
            }
        }
    }

    $requestBody = [];
    if (!empty($parameters) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        $formdata = [];
        foreach ($parameters as $param) {
            $fdata = [
                'key' => $param['key'],
                'type' => $param['type']
            ];
            if ($param['type'] === 'text') {
                $fdata['value'] = $param['value'];
            } else {
                $fdata['src'] = []; // For file
            }
            $fdata['description'] = $param['description'];
            $formdata[] = $fdata;
        }
        $requestBody = [
            'mode' => 'formdata',
            'formdata' => $formdata
        ];
    } elseif (!empty($parameters) && $method === 'GET') {
        $query = [];
        foreach ($parameters as $param) {
            $uri .= (str_contains($uri, '?') ? '&' : '?') . $param['key'] . '=' . $param['value'];
        }
    }

    $item = [
        'name' => $route->uri(),
        'request' => [
            'method' => $method,
            'header' => [
                ['key' => 'Accept', 'value' => 'application/json']
            ],
            'url' => [
                'raw' => $uri,
                'host' => ['{{base_url}}'],
                'path' => array_values(array_filter(explode('/', $route->uri())))
            ]
        ]
    ];

    if ($isAuth) {
        $item['request']['auth'] = [
            'type' => 'bearer',
            'bearer' => [
                ['key' => 'token', 'value' => '{{token}}', 'type' => 'string']
            ]
        ];
    }

    if (!empty($requestBody)) {
        $item['request']['body'] = $requestBody;
    }

    // Add path variables if they exist in URI (e.g. {id})
    if (preg_match_all('/\{([^}]+)\}/', $route->uri(), $pathVars)) {
        $variables = [];
        foreach ($pathVars[1] as $var) {
            $variables[] = [
                'key' => $var,
                'value' => '1' // Default
            ];
        }
        $item['request']['url']['variable'] = $variables;
    }

    if (!isset($folders[$folderName])) {
        $folders[$folderName] = [];
    }
    $folders[$folderName][] = $item;
}

$collectionItems = [];
foreach ($folders as $fName => $fItems) {
    $collectionItems[] = [
        'name' => $fName,
        'item' => $fItems
    ];
}

$collection = [
    'info' => [
        'name' => 'FoxGrooming API Collection',
        'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
    ],
    'item' => $collectionItems,
    'variable' => [
        [
            'key' => 'base_url',
            'value' => 'http://127.0.0.1:8000',
            'type' => 'string'
        ],
        [
            'key' => 'token',
            'value' => 'YOUR_TOKEN_HERE',
            'type' => 'string'
        ]
    ]
];

file_put_contents('postman_collection.json', json_encode($collection, JSON_PRETTY_PRINT));
echo "Collection generated successfully at postman_collection.json\n";
