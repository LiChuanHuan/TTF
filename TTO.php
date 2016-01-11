<?php
//----------------------------------------------
//       (The MIT License)
//       Tool Name: TTO(Table To Object)
//       Version: 1.0
//       Author:Larry.Li
//       email : nis131914@gmail.com
//       功能簡介：可以使用這個工具去直接從資料庫的表格產生PHP 、C# 類別檔
//       Description:You can use this tools to make PHP AND C# Class File from database table.
//       Copyright ©2015 仙化似風(國同學)
//----------------------------------------------
require_once(dirname(__FILE__) . '/TableCovertObjMethod/MakePHPClassFile.php');
require_once(dirname(__FILE__) . '/TableCovertObjMethod/MakeCSharpClassFile.php');
//set default data;
$phpObjDirName = "PHPObj"; // This is the Folder for Save the PHP Class File
$csharpObjDirName = "CSharpObj"; // This is the Folder for Save the C# Class File
$host = "localhost"; //Your database host
$user = "root"; //Your database user
$pwd = ""; //Your database user password
$dbName = "testdb"; //Your database Name
$TableName = 'user'; //This is table name in database

run();

//make class Obj
function run()
{
    global $phpObjDirName, $csharpObjDirName, $host, $user, $pwd, $dbName, $TableName;

    echo "Your select the table => $TableName ...<br>";

    checkObjDirectory($phpObjDirName);
    checkObjDirectory($csharpObjDirName);
    $dbc = mysqli_connect($host, $user, $pwd, $dbName) or die('error querying database.');
    mysqli_query($dbc, "SET NAMES 'utf8'");

    $query = "show FULL columns from $TableName;";
    $result = mysqli_query($dbc, $query) or die('error querying database.');

    $makePHPObj = new MakePHPClassFile($result, $phpObjDirName, $TableName);
    $makePHPObj->run();
    $makeCSharpObj = new MakeCSharpClassFile($result, $csharpObjDirName, $TableName);
    $makeCSharpObj->run();

    mysqli_close($dbc);
}

//check OBJ directory is exist
function checkObjDirectory($dirName)
{
    if (!is_dir($dirName)) {
        mkdir($dirName);
    }
}


?>