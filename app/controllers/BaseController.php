<?php
declare(strict_types=1);

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    public function initialize()
    {
        $this->tag->setTitle('THIS IS HOME.');
        $this->setCommonVariables();
    }

    protected function getTranslation()
    {
        // Read from 'accept-language' header
        $language = $this->request->getBestLanguage();

        $messages = [];

        $translationFile = app_path() . '/messages/' . $language . '.php';

        // Check if we have a translation file for that lang
        if (file_exists($translationFile)) {
            require $translationFile;
        } else {
            // Fallback to some default
            require  app_path() . '/messages/en.php';
        }

        // Return a translation object $messages comes from the require
        // statement above
        return new Phalcon\Translate\Adapter\NativeArray(
            [
                'content' => $messages,
            ]
        );
    }

    protected function setCommonVariables()
    {
        $routeName = $this->di->get('router')->getActionName();

        $this->view->setVars([
            'cache_life_time' => $this->di->get('config')->page_cache_secs,
            'environment' => env('ENV'),
            'route_name' => $routeName,
        ]);
    }

    public function hasErrors($result) : bool
    {
        if (!isset($result['messages'])) {
            throw new \Exception('Unexpected $result structure', 1);
        }
        return !empty($result['messages']);
    }

    public function existsById(string $modelName, $id, $keyName = 'id')
    {
        $result = false;
        if (!empty($id)) {
            $result = $this->di[$modelName]::findFirst([
                    $keyName . ' = :'. $keyName .':',
                    'bind' => [
                        $keyName => $id,
                    ]
                ]);
        }
        return $result;
    }

    public function modelUpdate($model, array $extra = [])
    {
        $messages = [];
        $data = [];
        if ($this->request->getJsonRawBody()) {
            $data = get_object_vars($this->request->getJsonRawBody());
        }
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }
        foreach ($extra as $key => $value) {
            $model->{$key} = $value;
        }
        if ($model->update() === false) {
            $messages = $model->getMessages();
        }
        return [ 'messages' => $this->extractValidationErrorMessage($messages), 'model' => $model];
    }

    public function modelCreate(string $modelName, array $extra = [])
    {
        $messages = [];
        $data = [];
        $model = new $this->di[$modelName];
        if ($this->request->getJsonRawBody()) {
            $data = get_object_vars($this->request->getJsonRawBody());
        }
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }
        foreach ($extra as $key => $value) {
            $model->{$key} = $value;
        }
        if ($model->create() === false) {
            $messages = $model->getMessages();
        }
        return [ 'messages' => $this->extractValidationErrorMessage($messages), 'model' => $model];
    }

    public function modelDelete($modelObject)
    {
        $messages = [];
        if ($modelObject->delete() === false) {
            $messages = $modelObject->getMessages();
        }
        return [ 'messages' => $this->extractValidationErrorMessage($messages), 'model' => $modelObject];
    }

    public function extractHeader($key)
    {
        $headers = $this->request->getHeaders();
        if (array_key_exists($key, $headers)) {
            return $headers[$key];
        }
        return null;
    }

    public function extractJsonBody($key, $default = null)
    {
        $output = $default;
        if (isset($this->request->getJsonRawBody()->{$key})) {
            $output = $this->request->getJsonRawBody()->{$key};
        }
        return $output;
    }

    public function getClientIPAddress()
    {
        return $this->request->getServer('HTTP_X_FORWARDED_FOR') ?? $this->request->getServer('REMOTE_ADDR');
    }

    public function getWebUrl()
    {
        return ($this->request->getServer('HTTP_X_FORWARDED_PROTO') ?? $this->request->getServer('REQUEST_SCHEME') ?? 'http') . '://' . $this->request->getServer('HTTP_HOST');
    }

    public function extractValidationErrorMessage($messages) : array
    {
        $output = [];
        foreach ($messages as $message) {
            $output[] = $message->getMessage();
        }
        return $output;
    }

    public function responseMessage(array $messages, $status = OK)
    {
        return $this->sendJson(['messages' => $messages], $status);
    }

    public function sendJson($data, $status = OK)
    {
        if ($this->request->isPost() == true && $this->request->isAjax()) {
            $data['csrf'] = $this->security->getToken();
        }
        $this->view->disable();
        $this->response->setStatusCode($status);
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($data));
        return $this->response;
    }

    public function loadResource($collection, array $resourcesList = [], string $action = 'addCss')
    {
        foreach ($resourcesList as $item) {
            if (!is_array($item)) {
                $collection->{$action}(getResourceFilename($item));
            } else {
                $file = $item[FILE_KEY];
                $cacheBuster = $item[CACHE_BUSTER_KEY];
                $collection->{$action}(getResourceFilename($file, $cacheBuster));
            }
        }
    }

    public function loadCssResources($collection, array $resourcesList = [])
    {
        $this->loadResource($collection, $resourcesList, 'addCss');
    }

    public function loadJsResources($collection, array $resourcesList = [])
    {
        $this->loadResource($collection, $resourcesList, 'addJs');
    }
}
