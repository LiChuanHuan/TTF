<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/11
 * Time: 15:13
 */

namespace Lib\Builder\LangFileBuilder;

use Lib\Builder\BuilderInterface;
use Lib\Db\TableInfo;

/**
 * Class 語言檔建立器
 * @package Lib\Builder\LangFileBuilder
 */
class LangFileBuilder implements BuilderInterface
{
    protected $enable;
    protected $tableInfo;
    protected $field_prefix;
    protected $outFolder;

    /**
     * LangFileBuilder constructor.
     * @param $tableInfo
     */
    public function __construct(TableInfo $tableInfo)
    {
        //設定這個建造器是否啟動
        $run_function =trim($_GET['runMethod']);
        $this->enable = stripos($run_function,"getlanguage");

        $this->tableInfo = $tableInfo;
        $this->field_prefix = strtoupper($this->tableInfo->getTableName());
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
     * 建立語言檔
     * @return bool 建立結果
     */
    public function builder()
    {
        $this->check_dir('lang');
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
        if(!is_dir(OUT_FOLDER.DIRECTORY_SEPARATOR.$dirname))
        {
            mkdir(OUT_FOLDER.DIRECTORY_SEPARATOR.$dirname);
        }

        $this->outFolder = OUT_FOLDER.DIRECTORY_SEPARATOR.$dirname;
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
     * 取得範本內容
     * @param $template_path 範本路徑
     * @return string 範本內容
     */
    public function get_templete($template_path)
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
        $template_content = $this->get_templete(dirname(__FILE__).DIRECTORY_SEPARATOR."template.php");
        $lang_file_name = strtolower(str_replace('_','',$this->tableInfo->getTableName()));
        $fp = fopen($this->getOutFolder().DIRECTORY_SEPARATOR.$lang_file_name.'.php', 'w');
        $template_content = str_replace("{t:content}",$content,$template_content);

        mb_convert_encoding($template_content, 'UTF-8');
        fwrite($fp, $template_content);
        fclose($fp);

        return 1;
    }

}

