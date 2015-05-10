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
	class MakePHPClassFile implements MakeClassFileInterface
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

		//Create PHP Class File Start
		function MakeClassFileStart($objDirName,$TableName)
		{
			echo "Start make PHP class in $objDirName Folder...<br>";
			$fp = fopen($objDirName."/".$TableName.'.php', 'w');
			$TableClass = "<?php\r\n".
			"class ".$TableName."\r\n".
			"{"."\r\n";
			mb_convert_encoding($TableClass, 'UTF-8');
			fwrite($fp, $TableClass);
			fclose($fp);
		}

		//Create PHP Class Field
		function MakeClassField($objDirName,$TableName, $Field, $Type, $Comment)
		{
			echo 'Field:'.$Field.' Type:'.$Type.' Comment:'.$Comment.'<br>';
			$fp = fopen($objDirName."/".$TableName.'.php', 'a');

			$TableClass = "    ".'//Type:'.$Type." \r\n". 
			"    ".'//Comment:'.$Comment." \r\n". 
			"    public $"."$Field".";\r\n";

			mb_convert_encoding($TableClass, 'UTF-8');
			fwrite($fp, $TableClass);
			fclose($fp);

		}

		//Create PHP Class End
		function MakeClassFildEnd($objDirName,$TableName)
		{
			$fp = fopen($objDirName."/".$TableName.'.php', 'a');
			$TableClass = "}\r\n".
			"?>";

			mb_convert_encoding($TableClass, 'UTF-8');
			fwrite($fp, $TableClass);
			fclose($fp);
			echo "End make class...<br>";
		}
	}
?>