<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST;
    if (isset($data["token"])) {
        if (tokentime($data)) {
            $data = (new Config())->getAll();
            _return_($data);
        } else {
            code(401);
        }
    } else {
        code(400);
    }
} else {
    code(400);
}
