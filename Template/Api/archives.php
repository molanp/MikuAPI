<?php
$id = $_REQUEST["id"] ?? NULL;
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (strcasecmp($id, "All") == 0) {
            $perpage = $_REQUEST["perpage"] ?? 20;
            if (!is_numeric($perpage) || intval($perpage) != $perpage) {
                _return_("invalid_perpage: The perpage must be a number.");
            }
            if ($perpage < 1 || $perpage > 101) {
                $perpage = 20;
            }
            if ($_REQUEST["type"]) {
                $result = $DATABASE->prepare("SELECT id, title, date, modified FROM miku_archives WHERE type = :type ORDER BY id DESC LIMIT $perpage");
                $result->execute([":type" => $_REQUEST["type"]]);
            } else {
                $result = $DATABASE->query("SELECT id, title, date, modified FROM miku_archives ORDER BY id DESC LIMIT $perpage");
            }
            _return_($result->fetchAll(PDO::FETCH_ASSOC));
        } else {
            $result = $DATABASE->prepare("SELECT title, content FROM miku_archives WHERE id = :id");
            $result->execute([":id" => $id]);
            _return_($result->fetch(PDO::FETCH_ASSOC));
        }
        break;
    case "DELETE":
        parse_str(file_get_contents("php://input"), $_REQUEST);
        if (tokentime($_REQUEST)) {
            $id = $_REQUEST["id"] ?? NULL;
            if (!empty($id)) {
                $s = $DATABASE->prepare("DELETE FROM miku_archives WHERE id = :id");
                $s->execute([":id" => $id]);
                code(200);
            } else {
                code(400);
            }
        } else {
            code(403);
        }
        break;
    case "POST":
        if (!isset($_REQUEST["id"])) {
            $result = $DATABASE->prepare("INSERT INTO miku_archives (title, content, author, type) VALUES (:title, :content, :author, :type)");
            $result->execute([
                ":title" => $_REQUEST["title"],
                ":content" => $_REQUEST["content"],
                ":author" => get_name($_REQUEST["token"]),
                ":type" => "announce",
            ]);
            _return_("发布成功");
        } else if (is_numeric($_REQUEST["id"])) {
            $result = $DATABASE->prepare("UPDATE miku_archives SET title = :title, content = :content WHERE id = :id");
            $result->execute([
                ":title" => $_REQUEST["title"],
                ":content" => $_REQUEST["content"],
                ":id" => $_REQUEST["id"]
            ]);
            _return_("修改成功");
        } else {
            _return_("不合法的ID", 400);
        }
        break;
    default:
        code(405);
}
