<?php
/**
 * Created by PhpStorm.
 * User: zhanara
 * Date: 19.10.17
 * Time: 16:05
 */

namespace App\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class LogsActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $router    = $container->get(RouterInterface::class);
        $template  = $container->has(TemplateRendererInterface::class)
                ? $container->get(TemplateRendererInterface::class)
                : null;
        $dbAdapter = $container->get(\Zend\Db\Adapter\Adapter::class);

        return new LogsAction($router, $template, $dbAdapter);
    }

}