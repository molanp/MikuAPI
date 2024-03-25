<?php
RequestLimit("10/min");
$data = [];
$data["title"] = __DATA__["title"];
$data["api"] = $DATABASE->query("SELECT COUNT(*) AS row_ FROM miku_api")->fetch()["row_"];
$data["count"] = $DATABASE->query("SELECT COUNT(*) AS count FROM miku_access WHERE url LIKE '/api%'")->fetch()["count"];
return_json($data);
