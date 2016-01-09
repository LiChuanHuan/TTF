<?php

namespace Lib\Db;

//----------------------------------------------
//       (The MIT License)
//       Tool Name: TTO(Table To Object)
//       Version: 1.0
//       Author:Larry.Li
//       email : nis131914@gmail.com
//       功能簡介：可以使用這個工具去直接從資料庫的表格產生相對應的PHP檔
//       Copyright ©2015 仙化似風(國同學)
//----------------------------------------------

/**
 * DB實體.
 */
class db_manager
{
    private static $instance;
    private static $db_table_name;

    private function __construct()
    {
    }

    /**
     * 取得DB實體.
     *
     * @param String $db_config_file db_config位置
     *
     * @return db_manager 實例
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            $db_config = json_decode(file_get_contents(dirname(__FILE__).'\db_config.json'));
            self::$db_table_name = $db_config->dbName;
            self::$instance = mysqli_connect($db_config->host, $db_config->user, $db_config->pwd, $db_config->dbName) or die('連接資料庫錯誤，請檢查使用者帳戶及密碼是否有權限.');
            mysqli_query(self::$instance, "SET NAMES 'utf8'");
        }

        return self::$instance;
    }

    /**
     * 取得目前的DB名稱.
     *
     * @return [type] [description]
     */
    public static function getDbName()
    {
        return self::$db_table_name;
    }

    /**
     * 取得資料庫中所有表的資訊，含表名、表注解
     *格式：[{"TABLE_NAME":"user","TABLE_COMMENT":"用戶資料表"}].
     * 
     * @return Array 所有資料表資訊陣列
     */
    public static function get_all_table_info()
    {
        $query = "select TABLE_NAME,TABLE_COMMENT from information_schema.tables where table_schema='".self::$db_table_name."'";
        $result = mysqli_query(self::$instance, $query) or die('查詢資料庫全資料表資料出錯');
        $table_info_arry = array();
        foreach ($result as $key => $row) {
            $one_table_info = array();
            foreach ($row as $key2 => $value) {
                $one_table_info[$key2] = $value;
            }
            $table_info_arry[$key] = $one_table_info;
        }

        return $table_info_arry;
    }
}
