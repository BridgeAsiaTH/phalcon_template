<?php

class IndexController extends BaseController
{
    public function initialize()
    {
        parent::initialize();
        $this->view->setMainView('index');
        // Overwrite default title
        // $this->tag->setTitle('TITLE');
        $this->t = $this->getTranslation();
    }

    public function indexAction()
    {
        $cacheKey = get_class($this).'_'.__FUNCTION__;

        $headerCollection = $this->assets->collection('header');
        $cssList = [
                STYLE_PREFIX.'bootstrap.css',
                [
                    FILE_KEY => STYLE_PREFIX.'style.css',
                    CACHE_BUSTER_KEY => CACHE_BUSTER,
                ],
            ];
        $this->loadCssResources($headerCollection, $cssList);

        $footerCollection = $this->assets->collection('footer');
        $jsList = [
                SCRIPT_PREFIX.'script.js',
                [
                    FILE_KEY => SCRIPT_PREFIX.'main.js',
                    CACHE_BUSTER_KEY => CACHE_BUSTER,
                ],
            ];
        $this->loadJsResources($footerCollection, $jsList);

        $this->view->setVars([
            'cache_key' => $cacheKey,
            'hi_with_name' => $this->t->_('hi-with-name', ['name' => 'test name.']),
            'hi' => $this->t->_('hi'),
            'bye' => $this->t->_('bye'),
        ]);

        // Use logger to investigate an issue.
        // $this->di->get('logger')->debug(print_r($variable, true));
    }

    public function route404Action()
    {
        return $this->responseMessage([PAGE_NOT_FOUND], NOT_FOUND);
    }
}
