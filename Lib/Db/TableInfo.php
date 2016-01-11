<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/11
 * Time: 14:20
 */

namespace Lib\Db;


/**
 * Class TableInfo
 * 資料表結構類
 * @package Lib\Db
 */
class TableInfo
{
    protected $db_manager;
    protected $table_name;
    protected $table_fields = array();

    /**
     * TableInfo constructor.
     * @param $table_name
     */
    public function __construct($table_name)
    {
        $this->db_manager = db_manager::getInstance();
        $this->table_name = $table_name;
        $this->setTableFields();
    }


    /**
     * 設定各字段的結構
     * @return array 二維陣列。 ex: array("filed_name"=>array("field"=>id,"Tyle"=>"bigint(20) unsigned"....)
     */
    public function setTableFields()
    {
        $query = "SHOW FULL COLUMNS FROM {$this->table_name}";
        $result = mysqli_query($this->db_manager, $query) or die("取得資料表結構發生錯誤！");

        foreach ($result as $key => $row) {
            $table_field = array();
            foreach ($row as $key2 => $value) {
                $table_field[$key2] = $value;
            }
            $this->table_fields[$key] = $table_field;
        }
    }

    /**
     * 取得各字段定義結構陣列
     * @return array ex: array("filed_name"=>array("field"=>id,"Tyle"=>"bigint(20) unsigned"....)
     */
    public function getTableFields()
    {
        return $this->table_fields;
    }

    /**
     * 取得表名
     * @return string tableName
     */
    public function getTableName()
    {
        return $this->table_name;
    }

}

