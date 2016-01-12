<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/12
 * Time: 16:05
 */
namespace Lib\Builder\ControllerFileBuilder;
use Lib\Builder\BaseFileBuilder;
use Lib\Db\TableInfo;

/**
 * 控制器類產生器
 * @package Lib\Builder\ControllerFileBuilder
 */
class ControllerFileBuilder extends BaseFileBuilder
{

    public function __construct(TableInfo $tableInfo)
    {
        parent::__construct($tableInfo);
        $this->_setEnable("getcontrol");
        $this->check_dir('Controller');
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