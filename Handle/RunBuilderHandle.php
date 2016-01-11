<?php namespace Handle;
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/11
 * Time: 17:27
 */
use Lib\Builder\LangFileBuilder\LangFileBuilder;
use Lib\Db\TableInfo;

if (isset($_GET['table_name'])) {
    $table_name = trim($_GET['table_name']);
    $lang_file_builder = new LangFileBuilder(new TableInfo($table_name));
    $result = new \stdClass();
    $result->result = $lang_file_builder->builder();
    echo json_encode($result);
}
