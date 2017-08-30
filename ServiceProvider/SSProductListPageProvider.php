<?php

namespace Plugin\SlnPayment\ServiceProvider;

use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;
use Silex\Provider\MonologServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Yaml\Yaml;

class SSProductListPageProvider implements ServiceProviderInterface
{

    public function register(BaseApplication $app)
    {
        $app['sln.payment.config'] = $app->share(function () use ($app) {
            return Yaml::parse(__DIR__ . '/../config.yml');
        });
    }

    public function boot(BaseApplication $app)
    {
    }
}
