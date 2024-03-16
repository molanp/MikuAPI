<?php
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $data = [];
        if (tokentime($_GET)) {
            $result = $DATABASE->query("SELECT DATE(time) AS date, COUNT(*) AS count FROM miku_access WHERE url LIKE '/api%' GROUP BY DATE(time) ORDER BY date ASC LIMIT 5");
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                array_push($data, ["date" => $row["date"], "count" => $row["count"]]);
            }
        } else {
            RequestLimit("10/min");
            $countsStmt = $DATABASE->prepare("SELECT miku_access.url, COUNT(*) AS count, miku_api.name FROM miku_access JOIN miku_api ON SUBSTRING(miku_access.url, 5) = miku_api.url_path WHERE miku_access.url LIKE '/api%' GROUP BY miku_access.url, miku_api.name ORDER BY count DESC LIMIT 10");
            $countsStmt->execute();
            $data = $countsStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        _return_($data);
    case "POST":
        if (tokentime($_POST)) {
            $data = [];
            $unit = $_POST['unit'];
            switch ($unit) {
                case 'year':
                    $query = "SELECT COUNT(*) AS count, miku_api.name FROM miku_access JOIN miku_api ON SUBSTRING(miku_access.url, 5) = miku_api.url_path WHERE miku_access.url LIKE '/api%' AND miku_access.time >= DATE_SUB(NOW(), INTERVAL 1 YEAR) GROUP BY miku_access.url, miku_api.name ORDER BY count DESC LIMIT 5";
                    break;
                case 'month':
                    $query = "SELECT COUNT(*) AS count, miku_api.name FROM miku_access JOIN miku_api ON SUBSTRING(miku_access.url, 5) = miku_api.url_path WHERE miku_access.url LIKE '/api%' AND miku_access.time >= DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY miku_access.url, miku_api.name ORDER BY count DESC LIMIT 5";
                    break;
                case 'week':
                    $query = "SELECT COUNT(*) AS count, miku_api.name FROM miku_access JOIN miku_api ON SUBSTRING(miku_access.url, 5) = miku_api.url_path WHERE miku_access.url LIKE '/api%' AND miku_access.time >= DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY miku_access.url, miku_api.name ORDER BY count DESC LIMIT 5";
                    break;
                case 'day':
                    $query = "SELECT COUNT(*) AS count, miku_api.name FROM miku_access JOIN miku_api ON SUBSTRING(miku_access.url, 5) = miku_api.url_path WHERE miku_access.url LIKE '/api%' AND miku_access.time >= DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY miku_access.url, miku_api.name ORDER BY count DESC LIMIT 5";
                    break;
                default:
                    code(400);
            }
            if (isset($query)) {
                $stmt = $DATABASE->prepare($query);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            _return_($data);
        } else {
            code(401);
        }
        break;
    default:
        code(405);
}
