<?php

namespace Plugin\SSProductListPage\ServiceProvider;

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
        
        $app->match('/' . $app["config"]["admin_route"] . '/ss/product/list/layout/{id}/edit',
            '\Plugin\SSProductListPage\Controller\LayoutController::index')
            ->assert('id', '\d+')->bind('ss_admin_product_list_layout_edit');
        
        $app->match('/' . $app["config"]["admin_route"] . '/ss/product/list/layout/{id}/preview', 
            '\Plugin\SSProductListPage\Controller\LayoutController::preview')
            ->assert('id', '\d+')->bind('ss_admin_product_list_layout_preview');
        
        $app['plugin.ss_product_list.repository.page_layout'] = $app->share(function () use ($app) {
            $pageLayoutRepository = $app['orm.em']->getRepository('Plugin\SSProductListPage\Entity\ProductListLayout');
            $pageLayoutRepository->setApplication($app);
        
            return $pageLayoutRepository;
        });
        
        $app['plugin.ss_product_list.repository.block_position'] = $app->share(function () use ($app) {
            $pageLayoutRepository = $app['orm.em']->getRepository('Plugin\SSProductListPage\Entity\ProductListBlockPosition');
        
            return $pageLayoutRepository;
        });
            
        //$app['twig']->addGlobal('PageLayout', $app['plugin.ss_product_list.repository.page_layout']->find(1));
    }

    public function boot(BaseApplication $app)
    {
    }
}
