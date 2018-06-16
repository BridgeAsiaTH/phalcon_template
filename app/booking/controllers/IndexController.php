<?php

namespace Booking\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $headerCollection = $this->assets->collection('header');
        $footerCollection = $this->assets->collection('footer');

        // $this->di->get('logger')->debug(print_r('from booking.', true));
    }
}
