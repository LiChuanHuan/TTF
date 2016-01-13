<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/11
 * Time: 15:13
 */

namespace Lib\Builder\LangFileBuilder;

use Lib\Builder\BaseFileBuilder;
use Lib\Db\TableInfo;

/**
 * 語言檔產生器
 * @package Lib\Builder\LangFileBuilder
 */
class LangFileBuilder extends BaseFileBuilder
{
    /**
     * LangFileBuilder constructor.
     * @param $tableInfo
     */
    public function __construct(TableInfo $tableInfo)
    {
        parent::__construct($tableInfo);
        $this->_setEnable("getlanguage");
        $this->check_dir('lang');
    }

    /**
     * 製作語言檔內容
     * @return string 語言檔內容
     */
    public function make_content()
    {
        $content = "";
        $fields = $this->tableInfo->getTableFields();
        foreach($fields as $field => $value)
        {
            $field_name = $this->field_prefix."_".strtoupper($value['Field']);
            $text = "\t'{$field_name}' => '{$value['Comment']}',\r\n";
            $content .= $text;
        }
        return $content;
    }


    /**
     * 寫入檔案
     * @param String $content 要寫入的內容，此內容會取代掉範本檔裡的{:content}
     * @return bool 寫入結果
     */
    public function write_file($content)
    {
        $template_content = $this->get_template(dirname(__FILE__).DIRECTORY_SEPARATOR."template.php");
        $lang_file_name = strtolower($this->controllerName);
        $fp = fopen($this->getOutFolder().DIRECTORY_SEPARATOR.$lang_file_name.'.php', 'w');
        $template_content = str_replace("{t:content}",$content,$template_content);

        mb_convert_encoding($template_content, 'UTF-8');
        fwrite($fp, $template_content);
        fclose($fp);

        return 1;
    }

}

