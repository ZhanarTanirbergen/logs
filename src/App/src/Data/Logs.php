<?php
/**
 * Created by PhpStorm.
 * User: zhanara
 * Date: 19.10.17
 * Time: 18:33
 */

namespace App\Data;

use Zend\Paginator\Adapter\AdapterInterface;

class Logs implements AdapterInterface
{
    protected $posts = [];

    public function __construct($dbAdapter)
    {
                $sqlViews = "SELECT * FROM `views` WHERE `id` = ?";
        $sqlRequests = "SELECT * FROM `requests` WHERE `id` = ?";

        $statement = $dbAdapter->createStatement("SELECT * FROM `logs`");
        $logs = $statement->execute()->getResource()->fetchAll();
        foreach ($logs as $row) {
            $resultViews = $dbAdapter->query($sqlViews, [$row['id_v']]);
            $resultRequests = $dbAdapter->query($sqlRequests, [$row['id_r']]);
            $view        = $resultViews->toArray()[0]['view'];
            $request     = $resultRequests->toArray()[0]['request'];
            $result      = [
                'view' => $view,
                'request' => $request
            ];
            array_push($this->posts, $result);

        }
    }

    public function count()
    {
        return count($this->posts);
    }

    public function getItems($offset, $itemCountPerPage)
    {
        return array_slice($this->posts, $offset, $itemCountPerPage);
    }
}
