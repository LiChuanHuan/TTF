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
	require_once(dirname(__FILE__).'/MakeClassFileInterface.php'); 
	class MakeCSharpClassFile implements MakeClassFileInterface
	{
		public $result;
		public $objDirName;
		public $TableName;
		public function __construct( $result, $objDirName, $TableName){
	       $this->result =  $result;
	       $this->objDirName = $objDirName;
	       $this->TableName = $TableName;
    	}
    	public function showMember()
    	{
    		echo $this->objDirName.'<br>';
    		echo $this->TableName.'<br>';
    	}

    	public function run()
    	{
    		$this->MakeClassFileStart($this->objDirName,$this->TableName);
			foreach ($this->result as $key => $row) {
				$this->MakeClassField($this->objDirName,$this->TableName, $row['Field'], $row['Type'], $row['Comment']);
			}
			$this->MakeClassFildEnd($this->objDirName,$this->TableName);
    	}

		//Create C# Class File Start
		function MakeClassFileStart($objDirName,$TableName)
		{
			echo "Start make C# class in $objDirName Folder...<br>";
			$fp = fopen($objDirName."/".$TableName.'.cs', 'w');
			$TableClass ="public class ".$TableName."\r\n".
			"{"."\r\n";
			mb_convert_encoding($TableClass, 'UTF-8');
			fwrite($fp, $TableClass);
			fclose($fp);
		}

		//Create C# Class Field
		function MakeClassField($objDirName,$TableName, $Field, $Type, $Comment)
		{
			echo 'Field:'.$Field.' Type:'.$Type.' Comment:'.$Comment.'<br>';
			$fp = fopen($objDirName."/".$TableName.'.cs', 'a');
			$Type = $this->ConvertType($Type);
			$TableClass = "    ".'///<summary>'." \r\n". 
			"    ".'///'.$Comment." \r\n". 
			"    ".'///</summary>'." \r\n".
			"    public $Type "."$Field".";\r\n";

			mb_convert_encoding($TableClass, 'UTF-8');
			fwrite($fp, $TableClass);
			fclose($fp);

		}

		//Create C# Class End
		function MakeClassFildEnd($objDirName,$TableName)
		{
			$fp = fopen($objDirName."/".$TableName.'.cs', 'a');
			$TableClass = "}\r\n";

			mb_convert_encoding($TableClass, 'UTF-8');
			fwrite($fp, $TableClass);
			fclose($fp);
			echo "End make class...<br>";
		}

		//Cover field type to C# member type 
		function ConvertType($Type)
		{
			$pos = strpos($Type, 'int');
			if($pos === false)
			{
				return 'string';
			}
			else
			{
				return 'int';
			}
		}
	}
?>