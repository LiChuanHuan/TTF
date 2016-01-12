<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/12
 * Time: 17:33
 */

namespace Lib\Builder;

use Lib\Db\TableInfo;

/**
 * 基本檔案生成控制器
 * @package Lib\Builder
 */
class BaseFileBuilder implements BuilderInterface
{
    protected $enable;
    protected $tableInfo;
    protected $field_prefix;
    protected $controllerName;
    protected $outFolder;

    public function __construct(TableInfo $tableInfo)
    {
        $this->tableInfo = $tableInfo;
        $this->field_prefix = strtoupper($this->tableInfo->getTableName());
        $this->controllerName = $this->getCtrlNameByTableName($this->tableInfo->getTableName());
        $this->outFolder = OUT_FOLDER;
        $this->check_dir($this->controllerName);
    }

    /**
     * 根據前台傳來的runMethod屬性中的字串是否包含$post_string來判斷控制器是否啟動
     * @param $post_string 檢查包含字串
     */
    protected function _setEnable($post_string)
    {
        $run_function = trim($_GET['runMethod']);
        $this->enable = stripos($run_function, $post_string);
    }

    /**
     * 建立語言檔
     * @return bool 建立結果
     */
    public function builder()
    {
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
        if (!is_dir($this->outFolder . DIRECTORY_SEPARATOR . $dirname)) {
            mkdir($this->outFolder . DIRECTORY_SEPARATOR . $dirname);
        }

        $this->outFolder .=  DIRECTORY_SEPARATOR . $dirname;
    }

    /**
     * 取得產生器的啟動狀態，注意，如果在建構子中沒有調用_setEnable($post_string)方法，則必定傳回0;
     * @return int
     */
    public function getEnable()
    {
        return isset($this->enable)?$this->enable:0;
    }

    /**
     * 取得控制器名稱
     * @param String $tableName 資料表名
     * @return string 控制器名
     */
    public function getCtrlNameByTableName($tableName)
    {
        $tableName = str_replace('pbet_', '', $tableName);
        $tableName = ucfirst($tableName);
        $tableName = str_replace('_', '', $tableName);
        return $tableName;
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
     * 回傳資料夾路徑
     * @return String 資料夾路徑
     */
    public function getOutFolder()
    {
        return $this->outFolder;
    }

    /**
     * 製作填充內容(需自己實作)
     * @return string Controller檔內容
     */
    public function make_content()
    {
        return "";
    }


    /**
     * 寫入檔案 (需自己實作)
     * @param mixed $content 要寫入的內容，此內容會取代掉範本檔裡的{t:....}相關標簽(注意，這邊的內容應該由make_content()產生)
     * @return bool 寫入結果
     */
    public function write_file($content)
    {
        return 1;
    }

}