<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/11
 * Time: 15:13
 */

namespace Lib\Builder\HtmlFileBuilder;

use Lib\Builder\BaseFileBuilder;
use Lib\Db\TableInfo;

/**
 * Html檔產生器
 * @package Lib\Builder\HtmlFileBuilder
 */
class HtmlFileBuilder extends BaseFileBuilder
{
    /**
     * HtmlFileBuilder constructor.
     * @param $tableInfo
     */
    public function __construct(TableInfo $tableInfo)
    {
        parent::__construct($tableInfo);
        $this->_setEnable("gethtml");
        $this->check_dir('html');
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

