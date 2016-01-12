<?php namespace Handle;
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/11
 * Time: 17:27
 */
use Lib\Builder\BuilderLeader;
use Lib\Builder\ControllerFileBuilder\ControllerFileBuilder;
use Lib\Builder\HtmlFileBuilder\HtmlFileBuilder;
use Lib\Builder\LangFileBuilder\LangFileBuilder;
use Lib\Db\TableInfo;

if (isset($_GET['table_name']) && isset($_GET['runMethod'])) {
    $table_name = trim($_GET['table_name']);
    $table_info = new TableInfo($table_name);
    $builder_leader = new BuilderLeader();
    $builder_leader->add(new HtmlFileBuilder($table_info));
    $builder_leader->add(new LangFileBuilder($table_info));
    $builder_leader->add(new ControllerFileBuilder($table_info));
    $result = new \stdClass();
    $result->result = $builder_leader->build();
    echo json_encode($result);
}
