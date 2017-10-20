<?php
/**
 * Created by PhpStorm.
 * User: zhanara
 * Date: 20.10.17
 * Time: 14:22
 */

namespace App\Action;

use App\Data\Views;
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

class ViewsAction implements ServerMiddlewareInterface
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
        $views = new Views($this->dbAdapter);
        $paginator = new Paginator($views);

        Paginator::setDefaultScrollingStyle(new Sliding());
        $paginator->setCurrentPageNumber(1);
        $paginator->setDefaultItemCountPerPage(3000);

        $data = [
            'paginator' => $paginator,
        ];
        return new HtmlResponse($this->template->render('app::views', $data));
    }
}