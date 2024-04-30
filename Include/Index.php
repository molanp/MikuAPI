<?php
$action = array_filter(explode("/", parse_url($_SERVER["REQUEST_URI"])["path"]))[1] ?? "home";
$path = addSlashIfNeeded(implode("/", array_slice(array_filter(explode("/", parse_url($_SERVER["REQUEST_URI"])["path"])), 1)));
define("__API_PATH__", $path);

if (!file_exists(__CORE_DIR__ . "/install.lock") && $action != "install") {
    jump("/install");
}
if (isset(__DATA__["close_site"], __DATA__["cc_protect"])) {
    if (__DATA__["close_site"] == "true" && $action != "miku" && $action != "v2") {
        die(include_once __TEMPLATE_DIR__ . "/Home/close.html");
    }

    if (__DATA__["cc_protect"] == "true" && $action != "miku" && $action != "v2") {
        include_once __INCLUDE_DIR__ . "/Firewall/CCProtect.php";
    }
}

if ($action != "install") {
    AddAccess();
}
switch ($action) {
    case "install":
        include_once __INCLUDE_DIR__ . "/Install.php";
        break;
    case "home":
        if (isset($_REQUEST['s'])) {
            include_once __TEMPLATE_DIR__ . "/Home/search.html";
        } else {
            include_once __TEMPLATE_DIR__ . "/Home/index.html";
        }
        break;
    case "miku":
        include_once __TEMPLATE_DIR__ . "/Admin/index.html";
        break;
    case "docs":
        include_once __TEMPLATE_DIR__ . "/Home/manager.html";
        break;
    case "api":
        include_once __CORE_DIR__ . "/loader.php";
        break;
    case "rank":
        include_once __TEMPLATE_DIR__ . "/Home/rank.html";
        break;
    case "v2":
        if (file_exists(__TEMPLATE_DIR__ . "/Api/" . substr(__API_PATH__, 0, -1) . ".php")) {
            include_once __TEMPLATE_DIR__ . "/Api/" . substr(__API_PATH__, 0, -1) . ".php";
        } else {
            include_once __TEMPLATE_DIR__ . "/Home/404.html";
        }
        break;
    case "archives":
        include_once __TEMPLATE_DIR__ . "/Home/archives.html";
        break;
    default:
        include_once __TEMPLATE_DIR__ . "/Home/404.html";
}
