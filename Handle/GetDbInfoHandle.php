<?php

namespace Handle;

use Lib\Db\db_manager;

db_manager::getInstance();
echo json_encode(db_manager::get_all_table_info());
