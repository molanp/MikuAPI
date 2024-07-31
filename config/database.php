<?php return array (
  'default' => 'production',
  'time_query_rule' => 
  array (
  ),
  'auto_timestamp' => true,
  'datetime_format' => 'Y-m-dH:i:s',
  'datetime_field' => '',
  'connections' => 
  array (
    'production' => 
    array (
      'type' => 'mysql',
      'hostname' => '127.0.0.1',
      'database' => 'root',
      'username' => 'root',
      'password' => '',
      'hostport' => '3306',
      'params' => 
      array (
        0 => 3,
        1 => 2,
      ),
      'charset' => 'utf8',
      'prefix' => '',
      'deploy' => 0,
      'rw_separate' => false,
      'master_num' => 1,
      'slave_no' => '',
      'fields_strict' => true,
      'break_reconnect' => false,
      'trigger_sql' => true,
      'fields_cache' => false,
    ),
  ),
);