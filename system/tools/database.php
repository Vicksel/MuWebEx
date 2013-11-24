<?php
/**
 *  MuWebEx
 *
 *  Created by Kristians Jaunzems on 13.24.11.
 *  Copyright RealDev 2013. All rights reserved.
 *
 */

if(!defined(WEB_ENGINE))
    die('Direct access to system modules is forbidden!');

/**
 * Class Database
 */
class Database
{
    /**
     * @var null
     */
    public static  $connection_link = NULL;
    /**
     * @var null
     */
    private        $data            = NULL;
    /**
     * @var null
     */
    public         $error           = NULL;
    /**
     * @var null
     */
    private        $stmt            = NULL;

    /**
     * @return bool
     */
    public static function initialize()
    {
        if(self::$connection_link == NULL)
        {
            $database        = array();
            $ServerHost      = '';

            require 'Application/config/database.php';

            self::$connection_link = sqlsrv_connect($ServerHost, $database);

            if(self::$connection_link === false)
            {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $value
     */
    public function AddParam($value)
    {
        $this->data[] =  Security::SecureSQLInputConvert($value);
    }

    /**
     * @param $array
     */
    public function AddParamArray($array)
    {
        $this->data = array_map(array('Security','SecureSQLInputConvert'),$array);
    }

    /**
     * @param $query
     */
    public function PrepareQuery($query)
    {
        $insertReview = sqlsrv_prepare(Database::$connection_link, $query, $this->data);

        if($insertReview === false)
        {
            $this->ReportErrors();
        }

        if(sqlsrv_execute($insertReview) === false)
        {
            $this->ReportErrors();
        }
    }

    /**
     * @param $where
     * @param $what
     */
    public function InsertQueryEx($where,$what)
    {
        $paramCount = count($this->data);

        if($paramCount > 0)
        {
            $params = '?';
            for($x = 1; $x < $paramCount;$x++)
            {
                $params = $params.',?';
            }

            $query = "INSERT INTO $where($what) VALUES($params)";

            $insertReview = sqlsrv_prepare(Database::$connection_link, $query,$this->data);

            if($insertReview === false)
            {
                $this->ReportErrors();
            }

            if($this->stmt = sqlsrv_execute($insertReview) === false)
            {
                $this->ReportErrors();
            }
        }
    }

    /**
     * @param $where
     * @param $what
     * @param bool $where2
     * @param null $value
     * @return bool
     */
    public function SelectQueryEx($where,$what,$where2= false,$value= null)
    {
        $sql    = ($where2 == false) ? '' : " WHERE $where2 = ?";
        $query  = "SELECT $what FROM $where". $sql;

        $this->stmt = sqlsrv_query(Database::$connection_link,$query,array($value));

        if($this->stmt === false)
        {
            $this->ReportErrors();
            return false;
        }
    }

    /**
     * @return array|false|null
     */
    public function GetAsArray()
    {
        return sqlsrv_fetch_array($this->stmt, SQLSRV_FETCH_ASSOC);
    }

    /**
     * @return bool
     */
    public function hasRows()
    {
        return sqlsrv_has_rows($this->stmt);
    }

    /**
     *
     */
    public function CloseSTMT()
    {
        sqlsrv_free_stmt($this->stmt);
    }

    /**
     *
     */
    public function ReportErrors()
    {
        $error =  sqlsrv_errors();

        if($error != NULL);
        {
            foreach($error as $key)
            {
                log::writeLog("SQLSTATE: ".$key['SQLSTATE']." CODE: ".$key[ 'code']." MESSAGE: ".$key[ 'message'],'Query Execution','sql-error');
            }
        }
    }

    /**
     * @return bool|null
     */
    public function ValidationErrors()
    {
        if($this->error != NULL);
        {
            return $this->error;
        }
        return false;
    }
}
