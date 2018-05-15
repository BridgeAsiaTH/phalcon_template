<?php

class IndexController extends BaseController
{
    public function initialize()
    {
        parent::initialize();
        // Overwrite default title
        // $this->tag->setTitle('TITLE');
        $this->setCommonVariables();
    }

    private function setCommonVariables()
    {
        $this->view->setVars([
            'cache_life_time' => $this->di->get('config')->page_cache_secs
        ]);
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
        ]);
    }
}
