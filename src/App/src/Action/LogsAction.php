<?php
/**
 * Created by PhpStorm.
 * User: zhanara
 * Date: 19.10.17
 * Time: 16:00
 */

namespace App\Action;

use App\Data\Logs;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Paginator\ScrollingStyle\Sliding;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class LogsAction implements ServerMiddlewareInterface
{
    private $router;

    private $template;

    private $dbAdapter;


    public function __construct(Router\RouterInterface $router, Template\TemplateRendererInterface $template = null, $dbAdapter)
    {
        $this->router    = $router;
        $this->template  = $template;
        $this->dbAdapter = $dbAdapter;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $logs = new Logs($this->dbAdapter);
        $paginator = new Paginator($logs);

        Paginator::setDefaultScrollingStyle(new Sliding());
        $paginator->setCurrentPageNumber(1);
        $paginator->setDefaultItemCountPerPage(3000);

        $data = [
            'paginator' => $paginator,
        ];
        return new HtmlResponse($this->template->render('app::logs', $data));
    }
}