<?php

namespace Moon\Db;

use PDO;
use PDOException;

/**
 * A class for PDO connection
 * @author TTT
 * @date 2016/1/29 9:55
 */
class Connection
{
    protected $pdo;
    protected $readPdo;

    protected $transactions = 0;
    protected $lastSql;
    protected $lastParams;

    public $dsn = 'mysql:host=localhost;dbname=test';
    public $username = 'root';
    public $password = '';
    public $charset = 'utf8';
    public $tablePrefix = '';
    public $emulatePrepares = false;
    public $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    /**
     * Slave configuration
     * [
     *      [
     *          'dsn' => 'mysql:host=localhost;dbname=test;port=3306',
     *          'username' => 'user1',
     *          'password' => 'pwd1',
     *          ...
     *      ],
     *      [
     *          'dsn' => 'mysql:host=localhost;dbname=test;port=3306',
     *          'username' => 'user2',
     *          'password' => 'pwd2',
     *          ...
     *      ],
     *      ...
     * ]
     * @var array
     */
    public $slaves = [];

    /**
     * return a master database PDO instance (insert, delete, update)
     * @return PDO
     */
    public function getPdo()
    {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }
        $config = [
            'dsn'=>$this->dsn,
            'username'=>$this->username,
            'password'=>$this->password,
            'options'=>$this->options,
            'emulatePrepares'=>$this->emulatePrepares,
        ];
        $this->pdo = $this->makePdo($config);
        return $this->pdo;
    }

    /**
     * create and return a PDO instance
     * @param array $config
     * @return PDO
     */
    protected function makePdo(array $config)
    {
        $pdo = new PDO($config['dsn'], $config['username'], $config['password'], $config['options']);

        if (constant('PDO::ATTR_EMULATE_PREPARES')) {
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, $config['emulatePrepares']);
        }

        $pdo->exec("SET NAMES {$this->charset}");

        return $pdo;
    }

    /**
     * return a PDO instance for query read (If in the transaction will use method getPdo() to use master db)
     * @return PDO
     */
    public function getReadPdo()
    {
        if ($this->transactions >= 1) {
            return $this->getPdo();
        }
        if ($this->readPdo instanceof PDO) {
            return $this->readPdo;
        }
        if (!is_array($this->slaves) || count($this->slaves) == 0) {
            return $this->getPdo();
        }

        $slaveDbConfig = $this->slaves;
        shuffle($slaveDbConfig);
        do {
            $config = array_shift($slaveDbConfig);

            try {
                $this->readPdo = $this->makePdo($config);
                return $this->readPdo;
            } catch (PDOException $e) {
                //pass
                //echo $e->getMessage();
            }
        } while (count($slaveDbConfig) > 0);

        return $this->readPdo = $this->getPdo();
    }

    /**
     * disconnect
     */
    public function disconnect()
    {
        $this->pdo = null;
        $this->readPdo = null;
    }

    /**
     * execute a query SQL
     * @param string $sql
     * @param array $bindParams
     * @return \PDOStatement | bool false
     */
    public function query($sql, array $bindParams = [])
    {
        $sql = $this->quoteSql($sql);
        $this->lastSql = $sql;
        $this->lastParams = $bindParams;
        $statement = $this->getReadPdo()->prepare($sql);
        $statement->execute($bindParams);
        return $statement;
    }

    /**
     * execute a SQL (type: insert 、delete 、update )，return the number of affected rows
     * @param string $sql
     * @param array $bindParams
     * @return int | bool false
     */
    public function execute($sql, array $bindParams = [])
    {
        $sql = $this->quoteSql($sql);
        $this->lastSql = $sql;
        $this->lastParams = $bindParams;
        $statement = $this->getPdo()->prepare($sql);
        if ($statement->execute($bindParams)) {
            return (int)$statement->rowCount();
        }
        return false;
    }

    /**
     * get last insert id
     * PDO::lastInsertId
     * @param null $sequence
     * @return int|string
     */
    public function getLastInsertId($sequence = null)
    {
        return $this->getPdo()->lastInsertId($sequence);
    }

    /**
     * begin transaction
     */
    public function beginTransaction()
    {
        ++$this->transactions;
        if ($this->transactions == 1) {
            $this->getPdo()->beginTransaction();
        }
    }
    /**
     * commit transaction
     */
    public function commit()
    {
        if ($this->transactions == 1) $this->getPdo()->commit();
        --$this->transactions;
    }

    /**
     * rollBack transaction
     */
    public function rollBack()
    {
        if ($this->transactions == 1) {
            $this->transactions = 0;
            $this->getPdo()->rollBack();
        } else {
            --$this->transactions;
        }
    }

    /**
     * @param string $sql
     * @return string
     */
    protected function quoteSql($sql)
    {
        //parse tableName
        if (preg_match_all("/{{\w+}}/", $sql, $matches)) {
            if (!empty($matches[0])) {
                foreach ($matches[0] as $val) {
                    $table = trim($val, "{{}}");
                    $sql = preg_replace("/$val/", '`' . $this->tablePrefix . $table . '`', $sql, 1);
                }
            }
        }
        return $sql;
    }

    /**
     * return last execute sql
     * @return string
     */
    public function getLastSql()
    {
        if (!empty($this->lastParams)) {
            foreach ($this->lastParams as $key => $val) {
                if (strpos($key, ':') === 0) {
                    $this->lastSql = preg_replace("/$key/", $this->getPdo()->quote($val), $this->lastSql, 1);
                } else {
                    $this->lastSql = preg_replace("/\?/", $this->getPdo()->quote($val), $this->lastSql, 1);
                }
            }
        }
        return $this->lastSql;
    }

    /**
     * fetch all rows
     * @param string $sql
     * @param array $bindParams
     * @param int $fetchStyle
     * @return array|bool|mixed
     */
    public function fetchAll($sql, array $bindParams = [], $fetchStyle = PDO::FETCH_ASSOC)
    {
        $statement = $this->query($sql, $bindParams);
        if ($statement) {
            $args = func_get_args();
            $args = array_slice($args, 2);
            $args[0] = $fetchStyle;
            if ($fetchStyle == PDO::FETCH_FUNC) {
                return call_user_func_array([$statement, 'fetchAll'], $args);
            }
            call_user_func_array([$statement, 'setFetchMode'], $args);
            return $statement->fetchAll();
        }
        return false;
    }

    /**
     * fetch first row
     * @param string $sql
     * @param array $bindParams
     * @param int $fetchStyle
     * @return array|bool false|mixed
     */
    public function fetch($sql, array $bindParams = [], $fetchStyle = PDO::FETCH_ASSOC)
    {
        $statement = $this->query($sql, $bindParams);
        if ($statement) {
            $args = func_get_args();
            $args = array_slice($args, 2);
            $args[0] = $fetchStyle;
            if ($fetchStyle == PDO::FETCH_FUNC) {
                return call_user_func_array([$statement, 'fetchAll'], $args);
            }
            call_user_func_array([$statement, 'setFetchMode'], $args);
            return $statement->fetch();
        }
        return false;
    }

    /**
     * Returns a scalar like: COUNT、AVG、MAX、MIN
     * @param string $sql
     * @param array $bindParams
     * @return bool false | mixed
     */
    public function scalar($sql, array $bindParams = [])
    {
        $statement = $this->query($sql, $bindParams);
        if ($statement && ($data = $statement->fetch(PDO::FETCH_NUM)) !== false) {
            if (isset($data[0])) {
                return $data[0];
            }
        }
        return false;
    }

    /**
     * insert
     * @param $tableName
     * @param array $insertData
     * @return bool false|int lastInsertId
     */
    public function insert($tableName, array $insertData = [])
    {
        if (empty($insertData)) {
            return false;
        }

        $fields = [];
        $bindFields = [];
        $values = [];
        foreach ($insertData as $key => $value) {
            $fields[] = $key;
            $bindFields[] = '?';
            $values[] = $value;
        }
        $sql = 'INSERT INTO ' . $tableName . '(' . implode($fields, ',') . ') VALUES(' . implode($bindFields, ',') . ')';

        $affectedRows = $this->execute($sql, $values);
        if ($affectedRows) {
            return $this->getLastInsertId();
        }
        return false;
    }

    /**
     * delete
     * @param string $tableName
     * @param string $where
     * @param array $bindParams
     * @return bool false|int affected rows
     */
    public function delete($tableName, $where = '', $bindParams = [])
    {
        $sql = 'DELETE FROM ' . $tableName . ' WHERE ' . $where;
        return $this->execute($sql, $bindParams);
    }

    /**
     * update
     * @param string $tableName
     * @param array $setData
     * @param string $where
     * @param array $bindParams
     * @return bool false|int affected rows
     */
    public function update($tableName, $setData, $where = '', $bindParams = [])
    {
        if (empty($setData)) {
            return false;
        }

        $fields = [];
        if (self::is_assoc($bindParams)) { // bind params using :
            $time = time();
            foreach ($setData as $key => $value) {
                $fields[] = "`$key`=:{$key}_set_" . $time;
                $bindParams[":{$key}_set_{$time}"] = $value;
            }
        } else {  // bind params using ?
            $bindSetParams = [];
            foreach ($setData as $key => $value) {
                $fields[] = "`$key`=?";
                $bindSetParams[] = $value;
            }
            if (!empty($bindParams)) {
                foreach ($bindParams as $v) {
                    $bindSetParams[] = $v;
                }
            }
            $bindParams = $bindSetParams;
        }

        $sql = 'UPDATE ' . $tableName . ' SET ' . implode($fields, ',');
        $sql .= ' WHERE ' . $where;

        return $this->execute($sql, $bindParams);
    }

    /**
     * from table
     * @param string $tableName
     * @return Table
     */
    public function from($tableName)
    {
        return new Table($tableName, $this);
    }

    /**
     * Check if it is an associative array
     * @param array $array
     * @return bool
     */
    protected static function is_assoc(array $array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
