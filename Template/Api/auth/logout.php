<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST;
    if (tokentime($data)) {
        $stmt = $DATABASE->prepare("UPDATE miku_users SET token = NULL WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        code(200);
    } else {
        code(401);
    }
} else {
    code(400);
}
