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
	interface MakeClassFileInterface
	{
	    public function __construct( $result, $objDirName, $TableName);
	   	public function showMember();
	   	public function run();
	   	function MakeClassFileStart($objDirName,$TableName);
	   	function MakeClassField($objDirName,$TableName, $Field, $Type, $Comment);
	   	function MakeClassFildEnd($objDirName,$TableName);
	}
?>