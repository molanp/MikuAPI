<?php

return [
    //默认使用的数据库连接配置
    'default' => 'production',

    //自定义时间查询规则
    'time_query_rule' => [],

    //自动写入时间戳字段
    //true为自动识别类型false关闭
    //字符串则明确指定时间字段类型支持int timestamp date timedate
    'auto_timestamp' => true,

    //时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-dH:i:s',

    //时间字段配置配置格式：create_time, update_time
    'datetime_field' => '',

    //数据库连接配置信息
    'connections' => [
        'production' => [
            //数据库类型
            'type' => 'mysql',
            //服务器地址
            'hostname' => '127.0.0.1',
            //数据库名
            'database' => 'root',
            //用户名
            'username' => 'root',
            //密码
            'password' => '',
            //端口
            'hostport' => '3306',
            //数据库连接参数
            'params' => [
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION
            ],
            //数据库编码默认采用utf8
            'charset' => 'utf8',
            //数据库表前缀
            'prefix' => '',
            //数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy' => 0,
            //数据库读写是否分离主从式有效
            'rw_separate' => false,
            //读写分离后主服务器数量
            'master_num' => 1,
            //指定从服务器序号
            'slave_no' => '',
            //是否严格检查字段是否存在
            'fields_strict' => true,
            //是否需要断线重连
            'break_reconnect' => false,
            //监听SQL
            'trigger_sql' => env('APP_DEBUG', true),
            //开启字段缓存
            'fields_cache' => false,
        ],
    ],
];
