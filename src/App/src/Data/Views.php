<?php
/**
 * Created by PhpStorm.
 * User: zhanara
 * Date: 20.10.17
 * Time: 14:24
 */

namespace App\Data;


use Zend\Paginator\Adapter\AdapterInterface;

class Views implements AdapterInterface
{
    protected $posts = [];


    public function __construct($dbAdapter)
    {
        $sqlViews     = "SELECT * FROM `views` WHERE `id` = ?";
        $statementTop = $dbAdapter->createStatement("SELECT `id_v`, COUNT(*) 
                                                            FROM `logs`
                                                            GROUP BY `id_v`
                                                            ORDER BY COUNT(*) DESC");
        $sqlCount = "SELECT COUNT(id) FROM `logs` WHERE `id_v` = ?";

        $views = $statementTop->execute()->getResource()->fetchAll();
        foreach ($views as $row) {
            $resultView = $dbAdapter->query($sqlViews, [$row['id_v']]);
            $view        = $resultView->toArray()[0]['view'];

            $resultCount = $dbAdapter->query($sqlCount, [$row['id_v']]);
            $count = $resultCount->toArray()[0]['COUNT(id)'];
            $result      = [
                'view' => $view,
                'count' => $count
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
