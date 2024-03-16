<?php
define('__ROOT_DIR__', dirname(__FILE__));
define(
    "__PLUGIN_DIR__",
    [
        "plugins",
        "extensive_plugins"
    ]
);
define("__CORE_DIR__", __ROOT_DIR__ . DIRECTORY_SEPARATOR . "Core");
define("__TEMPLATE_DIR__", __ROOT_DIR__ . DIRECTORY_SEPARATOR . "Template");
define("__MODULE_DIR__", __ROOT_DIR__ . DIRECTORY_SEPARATOR . "Module");
define("__INCLUDE_DIR__", __ROOT_DIR__ . DIRECTORY_SEPARATOR . "Include");
define(
    "__OPTION_LIST__",
    [
        "close_site" => [
            "value" => "false",
            "des" => "维护模式"
        ],
        "cc_protect" => [
            "value" => "true",
            "des" => "CC攻击保护"
        ],
        "theme_color" => [
            "value" => "#39C5BB",
            "des" => "主题色"
        ],
        "record" => [
            "value" => "00003900000",
            "des" => "备案号"
        ],
        "title" => [
            "value" => "MikuAPI",
            "des" => "站点标题"
        ],
        "copyright" => [
            "value" => "molanp 2024",
            "des" => "版权信息"
        ],
        "banner_title" => [
            "value" => "Banner标题",
            "des" => "横幅标题"
        ],
        "banner_description" => [
            "value" => "<b>Banner<b>文字",
            "des" => "横幅描述"
        ],
        "keywords" => [
            "value" => "API,api",
            "des" => "关键词"
        ],
        "links" => [
            "value" => "[GitHub](https://github.com/molanp/MikuAPI)\n[Issues](https://github.com/molanp/MikuAPI/issues)\n[开发指南](https://molanp.github.io/MikuAPI)",
            "des" => "友情链接，一行一个"
        ],
        "banner_image" => [
            "value" => "",
            "des" => "Banner显示图片，留空不显示"
        ]
    ]
);
