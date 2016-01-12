<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/12
 * Time: 16:05
 */
namespace Lib\Builder\ControllerFileBuilder;
use Lib\Builder\BuilderInterface;
use Lib\Db\TableInfo;


class ControllerFileBuilder implements BuilderInterface
{
    protected $enable;
    protected $tableInfo;
    protected $controllerName;
    protected $outFolder;

    public function __construct(TableInfo $tableInfo)
    {
        //設定這個建造器是否啟動
        $run_function = trim($_GET['runMethod']);
        $this->enable = stripos($run_function, "getcontrol");

        $this->tableInfo = $tableInfo;
        $this->controllerName = $this->getCtrlNameByTableName($this->tableInfo->getTableName());
        $this->outFolder = OUT_FOLDER;
    }

    public function builder()
    {
        $this->check_dir($this->controllerName);
        $this->check_dir('controller');
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
        $tableName = str_replace('pbet_', '', $tableName);
        $tableName = ucfirst($tableName);
        $tableName = str_replace('_', '', $tableName);
        return $tableName;
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
     * 製作Controller檔內容
     * @return string Controller檔內容
     */
    public function make_content()
    {
        $content = "";
        $fields = $this->tableInfo->getTableFields();
        foreach($fields as $field => $value)
        {
            if($value['Field'] == 'id')
            {
                $text = "\t\$data['id'] = \$id;\r\n";
            }
            $field_name = strtolower($value['Field']);
            $text = "\t\$data['{$field_name}'] = I('post.{$field_name}');\r\n";
            $content .= $text;
        }

        return $content;
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
     * @param String $content 要寫入的內容，此內容會取代掉範本檔裡的{t:....}相關標簽
     * @return bool 寫入結果
     */
    public function write_file($content)
    {
        $template_content = $this->get_template(dirname(__FILE__) . DIRECTORY_SEPARATOR . "template.php");
        $table_name = strtolower(str_replace('_', '', $this->tableInfo->getTableName()));
        $fp = fopen($this->getOutFolder() . DIRECTORY_SEPARATOR .$this->controllerName. 'Controller.class.php', 'w');

        $template_content = str_replace("{t:controller_name}", $this->controllerName, $template_content);
        $template_content = str_replace("{t:post_fields}", $content, $template_content);
        $template_content = str_replace("{t:table_name}",  $table_name, $template_content);

        mb_convert_encoding($template_content, 'UTF-8');
        fwrite($fp, $template_content);
        fclose($fp);

        return 1;
    }

}