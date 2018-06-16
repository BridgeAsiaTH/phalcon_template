<?php

namespace Booking;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();
        $prefix = app_path() . DIRECTORY_SEPARATOR . BOOKING_MODULE . DIRECTORY_SEPARATOR;
        $loader->registerNamespaces(
            [
                'Booking\Controllers' => $prefix . 'controllers' . DIRECTORY_SEPARATOR,
                'Booking\Models' => $prefix . 'models' . DIRECTORY_SEPARATOR,
            ]
        );

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        $environment = env('ENV') ?? 'prod';
        $config = require app_path().DIRECTORY_SEPARATOR.'config' . DIRECTORY_SEPARATOR . $environment . '-config.php';
        $prefix = app_path() . DIRECTORY_SEPARATOR . BOOKING_MODULE . DIRECTORY_SEPARATOR;

        $di->setShared('view', function () use ($prefix) {
            $view = new View();
            $view->setViewsDir($prefix . 'views' . DIRECTORY_SEPARATOR);
            $view->registerEngines([
                '.volt' => function ($view) {
                    $config = $this->getConfig();

                    $volt = new VoltEngine($view, $this);

                    $volt->setOptions([
                        'compiledPath' => $config->application->cacheDir,
                        'compiledSeparator' => '_',
                        'compileAlways' => env('ENV') !== 'prod',
                    ]);

                    return $volt;
                },
                '.phtml' => PhpEngine::class
            ]);
            return $view;
        });

        $di->setShared('logger', function () {
            return new FileLogger(base_path() . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . BOOKING_MODULE . '-' . date('Y-m-d').'.log');
        });

        $di->setShared('dispatcher', function () {
            $eventsManager = new Manager();

            // $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);

            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });
    }
}
