#TTO (Make File From DB Is Easy  )


##說明

你可以使用這個工具從資料庫的資料表去建立對應的檔案。
(目前的功能需求是針對thinkcmf2.0而設計的，不過依然可以很簡單的進行客制化)

###執行需求
php 5.4 以上
mysql 5.x 以上

###我要如何去使用這個工具呢？

####步驟1:
下載本項目的資料到本地資料夾


####步驟2:
編輯Lib\Db\db_config.json檔來設定資料庫的基本資料
```sh
"host":"localhost", //資料庫的路徑
"user":"root",  //資料庫的使用者
"pwd":"",   //資料庫的使用者密碼
"dbName":"testdb"   //要使用的資料庫名稱
```

####步驟3:
啟動伺服器並執行index.php

####步驟4:
針對資料表指定自己想要的操作。

###如何新增自訂義的檔案類型？
你可以在Lib\Builder裡新建一個新的資料夾，在資料夾裡新建一個自己的檔案產生器。
檔案產生器可以繼承BaseFileBuilder,然後你必需自己實作make_content()方法和write_file($content)方法。
這邊要特別注意的是建構子中的
```sh
parent::__construct($tableInfo);
$this->_setEnable("gethtml");
$this->check_dir('html');
```
_setEnable方法會從前端get方法取得runMethod參數值，根據方法參數來決定這個產生器是否啟動。
check_dir方法則是增加一層子目錄。如果這邊不調用，預設檔案會存在[\Out\資料表名\]中。

###假如有任何的問題！
你可以寄email: nis131914@gmail.com
或是
你也可以使用 skype:nis131914@hotmail.com

##感謝大家的使用。
