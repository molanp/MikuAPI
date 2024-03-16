<?php
switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (tokentime($_POST)) {
            $data = $_POST['data'];
            foreach ($data as $uuid => $value) {
                Update($DATABASE, "miku_api", ["top" => $value], ["uuid" => $uuid]);
            }
            code(200);
        } else {
            code(401);
        }
        break;
    default:
        code(405);
}
