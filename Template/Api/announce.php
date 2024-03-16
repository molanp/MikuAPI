<?php
RequestLimit("10/min");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $data = [];
    $result = $DATABASE->query("SELECT id, title, date FROM miku_archives WHERE type = 'announce' ORDER BY date DESC LIMIT 5");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        array_push($data, ["id" => $row["id"], "title" => $row["title"], "date" => $row["date"]]);
    }
    _return_($data);
}
