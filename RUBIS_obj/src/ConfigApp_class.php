<?php
/**
 * Created by PhpStorm.
 * User: potpov
 * Date: 08/05/17
 * Time: 13:29
 */

namespace Rubis;


class ConfigApp_class
{

    public static function GetConf() {
        return array(
            "root_dir" => "/PHP",
            "root_full_dir" => "/var/www/html/PHP"
        );
    }
}