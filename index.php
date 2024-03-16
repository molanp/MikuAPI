<?php
ini_set("date.timezone", "Asia/Shanghai");
require __DIR__ . DIRECTORY_SEPARATOR . "Config.inc.php";
require __MODULE_DIR__ . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . "Common.php";

$options = [];
if (file_exists(__CORE_DIR__ . DIRECTORY_SEPARATOR . "install.lock")) {
    if (file_exists(__CORE_DIR__ . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "connect.php")) {
        require_once __CORE_DIR__ . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "connect.php";
        $Data = new Data();
        $options = $Data->get("option");
    }
}
$server = [
    "php_version" => PHP_VERSION,
    "php_uname" => PHP_OS,
    "server_software" => $_SERVER["SERVER_SOFTWARE"],
    "upload_max_filesize" => get_cfg_var("upload_max_filesize") ? get_cfg_var("upload_max_filesize") : "不允许上传附件",
    "max_execution_time" => get_cfg_var("max_execution_time") . "秒 ",
    "memory_limit" => get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : "无"
];
$options["version"] = "0.0.1";
define("__CONFIG__", $server);
define("__DATA__", $options);
require __DIR__ . DIRECTORY_SEPARATOR . "Include" . DIRECTORY_SEPARATOR . "Index.php";
