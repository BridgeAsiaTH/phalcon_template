<?php

class HealthCheckController extends BaseController
{
    public function initialize()
    {
        parent::initialize();
        $this->view->disable();
    }

    public function healthAction()
    {
        $response = $this->responseMessage(['Healthy!'], OK);
        return $response;
    }
}
