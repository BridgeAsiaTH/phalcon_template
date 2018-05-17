<?php
use Phalcon\Mvc\Router;

$router = $di->getShared('router');
$router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);

$router->add('/', 'index::index');
$router->add('/health', 'healthCheck::health');

// Set 404 paths
$router->notFound('index::route404');
$router->handle();
