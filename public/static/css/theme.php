<?php
header("Content-type: text/css");
require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "Config.inc.php";
if (file_exists(__CORE_DIR__ . DIRECTORY_SEPARATOR .  DIRECTORY_SEPARATOR . "install.lock")) {
    require __CORE_DIR__ . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "connect.php";
    require __ROOT_DIR__ . DIRECTORY_SEPARATOR . "Module" . DIRECTORY_SEPARATOR . "Config.class.php";
    $url = (new Data())->get("option", "banner_image");
    $hex = str_replace("#", "", (new Data())->get("option", "theme_color"));
} else {
    $url = "";
    $hex = "39C5BB";
}
if (strlen($hex) == 3) {
    $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
    $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
    $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
} else {
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
}

$primary_container_light = implode(", ", array_map(function ($c) {
    return min(255, $c + 44);
}, [$r, $g, $b]));
$secondary_container_light = implode(", ", array_map(function ($c) {
    return min(255, $c + 43);
}, [$r, $g, $b]));
$background_light = implode(", ", array_map(function ($c) {
    return min(255, $c + 150);
}, [$r, $g, $b]));
$surface_container_high_light = implode(", ", array_map(function ($c) {
    return min(255, $c + 100);
}, [$r, $g, $b]));
$surface_container_highest_light = implode(", ", array_map(function ($c) {
    return min(255, $c + 160);
}, [$r, $g, $b]));
$on_secondary_container_dark = implode(", ", array_map(function ($c) {
    return min(255, $c + 150);
}, [$r, $g, $b]));

echo <<<CSS
:root {
    --mdui-color-primary: $r, $g, $b;
    --mdui-color-background-light: $background_light;
    --mdui-color-primary-container-light: $primary_container_light;
    --mdui-color-on-primary-container-light: 255, 255, 255;
    --mdui-color-secondary-container-light: $secondary_container_light;
    --mdui-color-on-secondary-container-light: 255, 255, 255;
    --mdui-color-surface-light: 255, 255, 255;
    --mdui-color-surface-container-light: 255, 255, 255;
    --mdui-color-surface-container-low-light: 255, 255, 255;
    --mdui-color-surface-container-highest-light: $surface_container_highest_light;
    --mdui-color-surface-container-high-light: 238, 250, 249;
    --mdui-color-primary-dark: 170, 170, 170;
    --mdui-color-primary-container-dark: 0, 0, 0;
    --mdui-color-background-dark: 10, 10, 10;
    --mdui-color-secondary-container-dark: 20, 20, 20;
    --mdui-color-on-primary-container-dark: 255, 255, 255;
    --mdui-color-on-secondary-container-dark: $on_secondary_container_dark;
    --mdui-color-surface-dark: 30, 30, 30;
    --mdui-color-surface-container-dark: 40, 40, 40;
    --mdui-color-surface-container-low-dark: 50, 50, 50;
    --mdui-color-surface-container-high-dark: 60, 60, 60;
    --mdui-color-surface-container-highest-dark: 70, 70, 70;
    --mdui-color-on-surface-variant-dark: 255, 255, 255;
    --mdui-color-on-primary-dark: 238, 238, 238;
}
CSS;
?>

.banner {
margin: auto;
text-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
background-color: rgb(var(--mdui-color-primary-container));
background-image: url('<?= $url ?>');
margin-bottom: 25px;
overflow: hidden;
background-position: center;
background-repeat: no-repeat;
background-size: cover;
}

::-webkit-scrollbar-thumb {
background-color: #<?= $hex ?>;
background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .4) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .4) 50%, rgba(255, 255, 255, .4) 75%, transparent 75%, transparent);
border-radius: 2em;
}