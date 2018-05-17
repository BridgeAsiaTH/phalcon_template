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
        // Test db create
        $data = [
            'key' => 'new key',
            'value' => 'new value',
        ];
        $result = $this->modelCreate('Config', $data);
        if ($this->hasErrors($result)) {
            return $this->responseMessage($result['messages'], BAD_REQUEST);
        }
        // Test db query and update
        $exists = $this->existsById('Config', 'new key', 'key');
        if (!$exists) {
            return $this->responseMessage(['Not found new key'], INTERNAL_SERVER_ERROR);
        }
        $data = [
            'value' => 'new value updated',
        ];
        $result = $this->modelUpdate($exists, $data);
        if ($this->hasErrors($result)) {
            return $this->responseMessage($result['messages'], BAD_REQUEST);
        }
        // Test db delete
        $result = $this->modelDelete($exists);
        if ($this->hasErrors($result)) {
            return $this->responseMessage($result['messages'], BAD_REQUEST);
        }
        return $response;
    }
}
