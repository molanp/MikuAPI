<?php

$sql = "SELECT name, class, method, url_path, file_path FROM miku_api WHERE url_path = :urlPath";
$statement = $DATABASE->prepare($sql);
$statement->execute([":urlPath" => __API_PATH__]);
$data = $statement->fetch(PDO::FETCH_ASSOC);
if (handle_check($data["name"]) && $_SERVER["REQUEST_METHOD"] == $data["method"] || $data["method"] == "ANY" || in_array($_SERVER["REQUEST_METHOD"], explode('/', $data["method"]))) {
    include_once __MODULE_DIR__ . "/logger.php";
    include_once $data["file_path"];
    $plugin = new $data["class"];
    $plugin->init($_REQUEST);
} else {
    code(405);
}
unset($plugin);
