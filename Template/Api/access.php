<?php
switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (tokentime($_POST)) {
            $page = $_POST["page"] ?? 1;
            if (!is_numeric($page) || $page <= 0 || floor($page) != $page) {
                _return_("不合法的页码", 400);
            }
            $page = ($page - 1) * 20;
            $stmt = $DATABASE->prepare("SELECT * FROM miku_access ORDER BY id DESC LIMIT 20 OFFSET :page");
            $stmt->bindValue(':page', (int)$page, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            _return_($data);
        } else {
            code(401);
        }
        break;
    case "DELETE":
        parse_str(file_get_contents("php://input"), $_REQUEST);
        if (tokentime($_REQUEST)) {
            $id = $_REQUEST["id"] ?? NULL;
            if (!empty($id)) {
                $s = $DATABASE->prepare("DELETE FROM miku_log WHERE id = :id");
                $s->execute([":id" => $id]);
                code(200);
            } else {
                code(400);
            }
        } else {
            code(403);
        }
        break;
    default:
        code(405);
}
