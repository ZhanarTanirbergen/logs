<?php
namespace App\Data;
/**
 * Created by PhpStorm.
 * User: zhanara
 * Date: 19.10.17
 * Time: 9:44
 */
class Parser
{
    public static function getData()
    {
        $content = file_get_contents("/home/zhanara/virtualhealth/view-analyzer.log");
        $content1 = file_get_contents("/home/zhanara/virtualhealth/view-analyzer.log.1");
        $a = explode(" ", $content);
        $b = explode(" ", $content1);
        $result = [];
        for($i=4; $i<count($a); $i+=10) {
            array_push($result, substr($a[$i], 0, -2));
        }
        for($i=4; $i<count($b); $i+=10) {
            array_push($result, substr($b[$i], 0, -2));
        }

        return $result;
    }
}