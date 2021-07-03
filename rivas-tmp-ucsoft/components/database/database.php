<?php
/** * * database.php * database class file * * @copyright    Copyright (C) 2012 Rivas Systems Inc. All rights reserved. * @versin    1.00 2011-02-05 * @author    Behnam Salili * */
require_once dirname(dirname(dirname(__file__))) . '/defines.php';

class Database
{
    /** * Hostname for the server * @var string $_hostName */
    private static $_hostName = "localhost";
    /** * User name to connect to database * @var string $_databaseUsername */
    private static $_databaseUsername = "root";
    /** * Password to connect to database * @var string $_databasePassword */
    private static $_databasePassword = "";
    /** * Database name * @var string $_databaseName */
    private static $_databaseName = "new_ucsoft";
    /** * Database connection * @var object   */
    private static $db_connection = null;

    /** * private function to not being used for creating instance */
    public function __construct()
    {
    }

    private static function get_db_connection()
    {
        if (self::$db_connection) {
            return self::$db_connection;
        }
        self::$db_connection = new mysqli(self::$_hostName, self::$_databaseUsername, self::$_databasePassword, self::$_databaseName);
        if (self::$db_connection->connect_error) throw new Exception(DATA_REGISTER_INTERRUPTION);
        self::$db_connection->set_charset("utf8");
        return self::$db_connection;
    }

    /** * runs a query and just returns the result and closes the connection * @return bool|mysqli_result */
    public static function execute_query($input)
    {
        $dblink = self::get_db_connection();
        $dbResult = $dblink->query($input);


        if (!$dbResult) throw new Exception(DATA_REGISTER_INTERRUPTION);

        return $dbResult;
    }

    public static function insertAndReturnInsertedId($input)
    {
        $dblink = self::get_db_connection();
        $dbResult = $dblink->query($input);


        if (!$dbResult) throw new Exception(DATA_REGISTER_INTERRUPTION);


        return $dblink->insert_id;
    }

    /** * @return num of rows of a query result */
    public static function num_of_rows($input)
    {
        return $input->num_rows;
    }

    /** * @return an associative array of current row of a query result */
    public static function get_assoc_array($input)
    {
        return $input->fetch_assoc();
    }

    /** * @return cleaned string to be used in a SQL query */
    public static function filter_str($inp)
    {
        if (get_magic_quotes_gpc()) $inp = stripslashes($inp);
        return self::get_db_connection()->real_escape_string($inp);
    }

    /** * @return true if the item exists in the table */
    public static function item_exists($item, $tableName, $fieldName)
    {
        $item = self::filter_str($item);
        $tableName = self::filter_str($tableName);
        $fieldName = self::filter_str($fieldName);
        try {
            $res = self::execute_query("SELECT * FROM `$tableName` WHERE `$fieldName` = '$item';");
            if (self::num_of_rows($res) > 0) return 'true'; else return 'false';
        } catch (exception $e) {
            return $e->getMessage();
        }
    }
}

