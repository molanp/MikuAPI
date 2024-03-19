<?php

$sql = "SELECT name, namespace, method, url, path FROM miku_api WHERE url = :url";
$statement = $DATABASE->prepare($sql);
$statement->execute([":url" => __API_PATH__]);
$data = $statement->fetch(PDO::FETCH_ASSOC);
if (handle_check($data["name"]) && $_SERVER["REQUEST_METHOD"] == $data["method"] || $data["method"] == "ANY" || in_array($_SERVER["REQUEST_METHOD"], explode('/', $data["method"]))) {
    include_once __MODULE_DIR__ . "/logger.php";
    include_once $data["path"];
	$namespace = $data["namespace"];
	$class = $namespace . "\\PluginHandler";
	$plugin = new $class;
	$plugin->init($_REQUEST);
} else {
    code(405);
}
unset($plugin);
