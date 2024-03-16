<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    RequestLimit("10/min");
    $data = $_POST;
    $type = $_POST["type"] ?? NULL;
    switch ($type) {
        case "pass":
            $pwd = hash("sha256", $data["new"]);
            if (tokentime($_POST)) {
                if ($pwd !== hash("sha256", $data["again"])) {
                    _return_("两次输入密码不同", 406);
                } else {
                    $stmt = $DATABASE->prepare("UPDATE miku_users SET password = :password WHERE token = :token or apikey = :token");
                    $stmt->bindParam(':password', $pwd);
                    $stmt->bindValue(':token', $_POST["token"] ?? $_POST["apikey"]);
                    $stmt->execute();
                    code(200);
                }
            } else {
                code(401);
            }
            break;
        default:
            if (isset($data["password"]) && isset($data["username"])) {
                $usr = $data["username"];
                $pwd = hash("sha256", $data["password"]);
                $stmt = $DATABASE->prepare("SELECT password FROM miku_users WHERE username = :username");
                $stmt->bindParam(':username', $usr);
                $stmt->execute();
                $result = $stmt->fetchColumn();
                if ($result == $pwd) {
                    $token = uniqid("swb_");
                    $hashedToken = hash("sha256", $token);
                    $stmt = $DATABASE->prepare("UPDATE miku_users SET token = :token WHERE username = :username");
                    $stmt->bindParam(':token', $hashedToken);
                    $stmt->bindParam(':username', $usr);
                    $stmt->execute();
                    _return_([
                        "user" => $data["username"],
                        "token" => $hashedToken
                    ]);
                } else {
                    _return_("账号或密码错误", 400);
                }
            } else {
                code(400);
            }
    }
}
