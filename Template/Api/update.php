<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && tokentime($_POST)) {
    $plugin_root = array_map(function ($item) {
        return __ROOT_DIR__ . DIRECTORY_SEPARATOR . $item;
    }, __PLUGIN_DIR__);
    $paths = getPath($plugin_root);
    $paths = array_map(function ($path) {
        return str_replace("/", DIRECTORY_SEPARATOR, $path);
    }, $paths);

    $pattern = "~(" . implode("|", $paths) . ")[/\\\\](.*)~";
    $conname = [];
    $pluginFiles = str_replace(["/", "\\"], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], find_files($plugin_root, ".php"));
    if (count($pluginFiles) > 0) {
        foreach ($pluginFiles as $pluginFilePath) {
            include_once __ROOT_DIR__ . DIRECTORY_SEPARATOR . $pluginFilePath;
            if (!empty($__PASS__)) {
                unset($__PASS__);
                continue;
            }
            $absolutePath = realpath(__ROOT_DIR__ . DIRECTORY_SEPARATOR . $pluginFilePath);
            $file = basename($absolutePath);
            $dir = dirname($absolutePath);
            $pluginClassName = pathinfo($file, PATHINFO_FILENAME);
            if (!class_exists($pluginClassName) && is_file($dir . DIRECTORY_SEPARATOR . $pluginClassName . ".php")) {
                $pluginClassName = basename($dir);
            }
            if (class_exists($pluginClassName)) {
                $type = defined("$pluginClassName::type") ? $pluginClassName::type : "一些工具";
                $path = addSlashIfNeeded(str_replace(
                    DIRECTORY_SEPARATOR,
                    "/",
                    str_replace(
                        __PLUGIN_DIR__,
                        "",
                        str_replace(
                            ".php",
                            "",
                            str_replace(
                                "index.php",
                                "",
                                $pluginFilePath
                            )
                        )
                    )
                ));
                $selectQuery = $DATABASE->prepare("SELECT uuid FROM miku_api WHERE file_path = :path");
                $selectQuery->execute([":path" => $absolutePath]);
                if ($result = $selectQuery->fetch(PDO::FETCH_ASSOC)) {
                    $uuid = $result["uuid"];
                    $data = [
                        "name" => $pluginClassName::name,
                        "version" => defined("$pluginClassName::version") ? $pluginClassName::version : "1.0",
                        "author" => defined("$pluginClassName::author") ? $pluginClassName::author : "Unknown",
                        "method" => defined("$pluginClassName::method") ? $pluginClassName::method : "ANY",
                        "profile" => defined("$pluginClassName::profile") ? $pluginClassName::profile : "",
                        "request" => re_par(defined("$pluginClassName::request") ? $pluginClassName::request : []),
                        "response" => re_par(defined("$pluginClassName::response") ? $pluginClassName::response : []),
                        "type" => $type,
                        "time" => date("Y-m-d H:i:s", time())
                    ];
                    Update($DATABASE, "miku_api", $data, ["uuid" => $uuid]);
                } else {
                    $uuid = sprintf(
                        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                        mt_rand(0, 0xffff),
                        mt_rand(0, 0xffff),
                        mt_rand(0, 0xffff),
                        mt_rand(0, 0x0fff) | 0x4000,
                        mt_rand(0, 0x3fff) | 0x8000,
                        mt_rand(0, 0xffff),
                        mt_rand(0, 0xffff),
                        mt_rand(0, 0xffff)
                    );
                    $data = [
                        "uuid" => $uuid,
                        "name" => $pluginClassName::name,
                        "version" => defined("$pluginClassName::version") ? $pluginClassName::version : "1.0",
                        "author" => defined("$pluginClassName::author") ? $pluginClassName::author : "Unknown",
                        "method" => defined("$pluginClassName::method") ? $pluginClassName::method : "ANY",
                        "profile" => defined("$pluginClassName::profile") ? $pluginClassName::profile : "",
                        "request" => re_par(defined("$pluginClassName::request") ? $pluginClassName::request : []),
                        "response" => re_par(defined("$pluginClassName::response") ? $pluginClassName::response : []),
                        "class" => $pluginClassName,
                        "url_path" => $path,
                        "file_path" => $absolutePath,
                        "type" => $type,
                        "top" => "false",
                        "status" => "true"
                    ];
                    Insert($DATABASE, "miku_api", $data);
                }
            } else {
                (new logger())->warn("插件文件缺少主类，文件路径：$pluginFilePath ，文件名：$file");
            }
        }
    }
    try {
        $threshold = date("Y-m-d H:i:s", time() - (60 * 30));
        $query_delete = "DELETE FROM miku_api WHERE time < :threshold";
        $stmt_delete = $DATABASE->prepare($query_delete);
        $stmt_delete->bindParam(":threshold", $threshold, PDO::PARAM_INT);
        $stmt_delete->execute();
        $rowCount = $stmt_delete->rowCount();
        if ($rowCount != 0) {
            (new logger())->info("删除 $rowCount 条过期API记录。");
        }
    } catch (PDOException $e) {
        (new logger())->error("删除过期API数据时出错: " . $e->getMessage());
    }
    code(200);
} else {
    code(401);
}
