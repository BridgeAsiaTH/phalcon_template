<?php
use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();
    $configPath = app_path() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
    /**
     * Read services
     */
    include $configPath . 'services.php';

    /**
     * Handle routes
     */
    include  $configPath . 'router.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include $configPath . 'loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    // Register the installed modules
    $application->registerModules(
        [
            BOOKING_MODULE => [
                'className' => 'Booking\Module',
                'path'      => app_path() . DIRECTORY_SEPARATOR . BOOKING_MODULE . DIRECTORY_SEPARATOR . 'Module.php',
            ],
        ]
    );

    echo $application->handle()->getContent();
} catch (\Exception $e) {
    $exceptionRef = time();
    $di['logger']->error($exceptionRef . ' ' . $e->getMessage());
    $di['logger']->error($exceptionRef . ' ' . $e->getTraceAsString());

    $di['response']->setStatusCode(INTERNAL_SERVER_ERROR);
    $di['response']->setContentType('application/json', 'UTF-8');
    $di['response']->setContent(json_encode(['message' => 'Internal server error with reference ' . $exceptionRef]));
    $di['response']->send();
}
