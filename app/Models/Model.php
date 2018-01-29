<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/29
 * Time: 13:49
 */

namespace App\Models;

use Moon\Db\Connection;
use Moon\Db\Table;

class Model extends Table
{
    public function __construct($tableName = null, $db = null)
    {
        if(is_null($db)){
            $db = \Moon::$app->get('db');
            if(is_null($db)){
                $config = config('db');
                $db = new Connection($config);
                \Moon::$app->add('db', $db);
            }
        }
        parent::__construct($tableName, $db);
    }
}