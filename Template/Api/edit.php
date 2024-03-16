<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $for = $_POST["for"] ?? NULL;
    $token = $_POST;
    switch ($for) {
        case "option":
            if (tokentime($token)) {
                $WEB = new Data();
                foreach ($_POST["data"] as $key => $value) {
                    $WEB->update("option", [$key => $value]);
                }
                code(200);
            } else {
                code(401);
            }
            break;
        case "status":
            if (tokentime($token)) {
                $keys = array_keys($_POST["data"]);
                $value = array_values($_POST["data"]);
                $i = 0;
                for ($i; $i < count($value); $i++) {
                    $sql = "UPDATE miku_api SET status = :status WHERE uuid = :uuid";
                    $stmt = $DATABASE->prepare($sql);
                    $stmt->bindParam(':status', $value[$i]);
                    $stmt->bindParam(':uuid', $keys[$i]);
                    $stmt->execute();
                }
                code(200);
            } else {
                code(401);
            }
            break;
    }
}
