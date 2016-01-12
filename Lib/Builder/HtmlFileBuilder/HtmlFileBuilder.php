<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/11
 * Time: 15:13
 */

namespace Lib\Builder\HtmlFileBuilder;

use Lib\Builder\BuilderInterface;
use Lib\Db\TableInfo;

/**
 * Class Html檔建立器
 * @package Lib\Builder\HtmlFileBuilder
 */
class HtmlFileBuilder implements BuilderInterface
{
    protected $enable;
    protected $tableInfo;
    protected $field_prefix;
    protected $controllerName;
    protected $outFolder;

    /**
     * HtmlFileBuilder constructor.
     * @param $tableInfo
     */
    public function __construct(TableInfo $tableInfo)
    {
        //設定這個建造器是否啟動
        $run_function =trim($_GET['runMethod']);
        $this->enable = stripos($run_function,"gethtml");

        $this->tableInfo = $tableInfo;
        $this->field_prefix = strtoupper($this->tableInfo->getTableName());
        $this->controllerName = $this->getCtrlNameByTableName($this->tableInfo->getTableName());
        $this->outFolder = OUT_FOLDER;
    }

    /**
     * 取得啟動值
     * @return int
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * 取得控制器名稱
     * @param String $tableName 資料表名
     * @return string 控制器名
     */
    public function getCtrlNameByTableName($tableName)
    {
        $tableName = str_replace('pbet_','',$tableName);
        $tableName = ucfirst($tableName);
        $tableName = str_replace('_','',$tableName);
        return $tableName;
    }


    /**
     * 建立語言檔
     * @return bool 建立結果
     */
    public function builder()
    {
        $this->check_dir($this->controllerName);
        $this->check_dir('html');
        $content = $this->make_content();
        $resulte = $this->write_file($content);
        return $resulte;

    }

    /**
     * 檢查資料夾是否存在，如果不存在就建立一個新的資料夾。
     * 建立完成後會將資料夾的路徑保存到$this->outFolder。
     * @param $dirname 資料夾名稱
     */
    public function check_dir($dirname)
    {
        if(!is_dir($this->outFolder.DIRECTORY_SEPARATOR.$dirname))
        {
            mkdir($this->outFolder.DIRECTORY_SEPARATOR.$dirname);
        }

        $this->outFolder .= DIRECTORY_SEPARATOR.$dirname;
    }


    /**
     * 回傳資料夾路徑
     * @return String 資料夾路徑
     */
    public function getOutFolder()
    {
        return $this->outFolder;
    }

    /**
     * 製作HTML檔內容
     * @return string HTML檔內容
     */
    public function make_content()
    {
        $content = new \stdClass();
        $content->table_head = $this->make_table_head();
        $content->data_table_columns = $this->make_data_table_columns();
        return $content;
    }

    /**
     * 建立表頭字串
     * @return string 表頭字串
     */
    public function make_table_head()
    {
        $table_head = "";
        $fields = $this->tableInfo->getTableFields();
        foreach ($fields as $field => $value) {
            $field_name = $this->field_prefix . "_" . strtoupper($value['Field']);
            $text = "\t<th>{:L('{$field_name}')}</th>\r\n";
            $table_head .= $text;
        }
        return $table_head;
    }

    /**
     * 建立data_table的欄位字串
     * @return string data_table的欄位字串
     */
    public function make_data_table_columns()
    {
        $data_table_columns = "";
        $fields = $this->tableInfo->getTableFields();
        foreach ($fields as $field => $value) {
            $field_name = strtolower($value['Field']);
            $text = "\t\t{\"data\": \"{$field_name}\"},\r\n";
            $data_table_columns .= $text;
        }
        return $data_table_columns;
    }


    /**
     * 取得範本內容
     * @param $template_path 範本路徑
     * @return string 範本內容
     */
    public function get_template($template_path)
    {
        $template_content = file_get_contents($template_path);
        return $template_content;
    }

    /**
     * 寫入檔案
     * @param String $content 要寫入的內容，此內容會取代掉範本檔裡的{:content}
     * @return bool 寫入結果
     */
    public function write_file($content)
    {
        $template_content = $this->get_template(dirname(__FILE__) . DIRECTORY_SEPARATOR . "template.html");
        $html_file_name = strtolower(str_replace('_', '', $this->tableInfo->getTableName()));
        $fp = fopen($this->getOutFolder() . DIRECTORY_SEPARATOR .$html_file_name.'_index.html', 'w');

        $template_content = str_replace("{t:ctrlName}", $this->controllerName, $template_content);
        $template_content = str_replace("{t:table_head}", $content->table_head, $template_content);
        $template_content = str_replace("{t:columns}",$content->data_table_columns, $template_content);

        mb_convert_encoding($template_content, 'UTF-8');
        fwrite($fp, $template_content);
        fclose($fp);

        return 1;
    }

}

