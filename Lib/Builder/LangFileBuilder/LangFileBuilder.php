<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/11
 * Time: 15:13
 */

namespace Lib\Builder\LangFileBuilder;

use Lib\Db\TableInfo;

/**
 * Class 語言檔建立器
 * @package Lib\Builder\LangFileBuilder
 */
class LangFileBuilder
{
    protected $tableInfo;
    protected $field_prefix;
    protected $outFolder;

    /**
     * LangFileBuilder constructor.
     * @param $tableInfo
     */
    public function __construct(TableInfo $tableInfo)
    {
        $this->tableInfo = $tableInfo;
        $this->field_prefix = strtoupper($this->tableInfo->getTableName());
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
     * @param $templetepath 範本路徑
     * @return string 範本內容
     */
    public function get_templete($templetepath)
    {
        $templete_content = file_get_contents($templetepath);
        return $templete_content;
    }

    /**
     * 寫入檔案
     * @param String $content 要寫入的內容，此內容會取代掉範本檔裡的{:content}
     * @return bool 寫入結果
     */
    public function write_file($content)
    {
        $templete_content = $this->get_templete(dirname(__FILE__).DIRECTORY_SEPARATOR."templete.php");
        $lang_file_name = strtolower(str_replace('_','',$this->tableInfo->getTableName()));
        $fp = fopen($this->getOutFolder().DIRECTORY_SEPARATOR.$lang_file_name.'.php', 'w');
        $templete_content = str_replace("{:content}",$content,$templete_content);

        mb_convert_encoding($templete_content, 'UTF-8');
        fwrite($fp, $templete_content);
        fclose($fp);

        return 1;
    }

}

