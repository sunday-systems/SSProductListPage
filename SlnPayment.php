<?php

namespace Plugin\SSProductListPageProvider;

class SlnPayment {

    /**
     * @var \Eccube\Application
     */
    private $app;

    public function __construct(\Eccube\Application $app) 
    {
        $this->app = $app;
    }
}

