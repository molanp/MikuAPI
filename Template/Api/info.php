<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    RequestLimit("10/min");
    $for = $_GET["for"] ?? NULL;
    switch ($for) {
        case "api":
            $urlPath = $_GET["url"] ?? "";
            preg_match("#/docs/(.*)#", $urlPath, $urlPath);
            $urlPath = addSlashIfNeeded($urlPath[1] ?? "");
            $statement = $DATABASE->prepare("SELECT name, version, author, method, profile, request, response, type, status, time FROM miku_api WHERE url_path = :urlPath");
            $statement->execute([":urlPath" => $urlPath]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $data = [];
            if ($result) {
                foreach ($result as $column => $value) {
                    $conname[$column] = $value;
                };
                $stmt = $DATABASE->prepare("SELECT COUNT(*) AS count FROM miku_access WHERE url = '/api$urlPath'");
                $stmt->execute();
                $count = $stmt->fetch(PDO::FETCH_ASSOC)["count"] ?? 0;
                $conname["count"] = $count;
            } else {
                _return_("Not Found.", 404);
            }
            break;
        case "options":
            $conname = (new Data())->get("option");
            $conname["version"] = __DATA__["version"];
            break;
        case "status":
            $conname = [];
            $query = $DATABASE->prepare("SELECT name, top, uuid, type, status, author, version, class FROM miku_api ORDER BY top DESC, name");
            $query->execute();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                array_push($conname, $row);
            }
            break;
        default:
            $limit = 12;
            $pages = $_GET["page"] ?? 1;
            if (!is_numeric($pages) || $pages <= 0 || floor($pages) != $pages) {
                _return_("不合法的页码", 400);
            }
            $pages = ($pages - 1) * $limit;

            $query = $DATABASE->prepare("SELECT type, top, name, author, url_path, profile, status, time FROM miku_api ORDER BY top DESC, name LIMIT :limit OFFSET :pages");
            $query->execute([":limit" => $limit, ":pages" => $pages]);
            $conname = [];
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $url_path = $row["url_path"];
                $stmt = $DATABASE->prepare("SELECT COUNT(*) AS count FROM miku_access WHERE url = '/api$url_path'");
                $stmt->execute();
                $conname[$row["name"]] = [
                    "author" => $row["author"],
                    "path" => $url_path,
                    "type" => $row["type"],
                    "count" => $stmt->fetch(PDO::FETCH_ASSOC)["count"] ?? 0,
                    "api_profile" => $row["profile"],
                    "status" => $row["status"],
                    "time" => $row["time"]
                ];
            }

            break;
    }
    _return_($conname);
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (tokentime($_POST)) {
        $for = $_POST["for"] ?? NULL;
        $conname = [];
        switch ($for) {
            case "options":
                $conname = (new Data())->get("option", NULL, true);
                break;
            case "update":
                $dbData = [];
                $stmt = $DATABASE->query("SELECT idx FROM miku_data WHERE belong = 'option'");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $dbData[] = $row["idx"];
                }

                $itemsToAdd = array_diff(array_keys(__OPTION_LIST__), $dbData);
                $itmsToDelete = array_diff($dbData, array_keys(__OPTION_LIST__));

                if (!empty($itemsToAdd)) {
                    $sqlAdd = "REPLACE INTO miku_data (idx, content, des, belong) VALUES (:idx, :value, :des, 'option')";
                    $stmtAdd = $DATABASE->prepare($sqlAdd);
                    foreach ($itemsToAdd as $item) {
                        $stmtAdd->bindParam(":idx", $item);
                        $stmtAdd->bindValue(":value", __OPTION_LIST__[$item]["value"]);
                        $stmtAdd->bindValue(":des", __OPTION_LIST__[$item]["des"]);
                        $stmtAdd->execute();
                    }
                }

                if (!empty($itemsToDelete)) {
                    $sqlDelete = "DELETE FROM miku_data WHERE idx = :idx AND belong = 'option'";
                    $stmtDelete = $DATABASE->prepare($sqlDelete);
                    foreach ($itemsToDelete as $item) {
                        $stmtDelete->bindParam(":idx", $item);
                        $stmtDelete->execute();
                    }
                }
                (new logger())->info("用户执行更新设置项操作");
                $conname = "更新成功";
                break;
        }
    } else {
        code(401);
    }
    _return_($conname);
}
