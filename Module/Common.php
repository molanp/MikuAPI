<?php
include_once __MODULE_DIR__ . '/Config.class.php';
include_once __MODULE_DIR__ . '/requests.php';
include_once __MODULE_DIR__ . '/watchdog.php';
include_once __MODULE_DIR__ . '/logger.php';

/**
 * 跳转到某一链接
 * 
 * @param string  $url 链接
 * @return void
 **/
function jump($url)
{
    die(header('Location: ' . $url));
}

/**
 * 向数据库表中插入一行数据。
 *
 * @param PDO $database 表示数据库连接的 PDO 对象。
 * @param string $table 要插入数据的表名。
 * @param array $data 包含列名和对应值的关联数组。
 *
 * @return void
 */
function Insert($database, $table, $data)
{
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $database->prepare($sql);
    $stmt->execute($data);
}

/**
 * 更新数据库表中的一行或多行数据。
 *
 * @param PDO $database 表示数据库连接的 PDO 对象。
 * @param string $table 要更新的表名。
 * @param array $data 包含列名和对应值的关联数组。
 * @param array $where 作为更新查询 WHERE 子句的列名和值的关联数组。
 *
 * @return void
 */
function Update($database, $table, $data, $where)
{
    $setColumns = "";
    foreach ($data as $key => $value) {
        $setColumns .= "$key=:$key, ";
    }
    $setColumns = rtrim($setColumns, ", ");
    $whereClause = "";
    foreach ($where as $key => $value) {
        $whereClause .= "$key=:$key AND ";
    }
    $whereClause = rtrim($whereClause, "AND ");
    $sql = "UPDATE $table SET $setColumns WHERE $whereClause";
    $stmt = $database->prepare($sql);
    $stmt->execute(array_merge($data, $where));
}

/**
 * 验证请求参数中的token或apikey是否有效
 * 
 * @param array $data HTTP传参
 * @return bool
 **/
function tokentime($data)
{
    global $DATABASE;

    $token = $data["token"] ?? $data["apikey"] ?? "miku";
    $stmt = $DATABASE->prepare("SELECT logtime, username FROM miku_users WHERE token = :token OR apikey = :token");
    $stmt->execute([':token' => $token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return false;
    }

    if (time() - strtotime($row["logtime"]) < 60 * 30 || $row["username"]) {
        return true;
    } else {
        return false;
    }
}

/**
 * 根据给定的token或apikey查询用户名
 * 
 * @param string $data
 * @param bool $array $data是否为请求参数数组
 * @return mixed
 */
function get_name($data, $array=false)
{
    global $DATABASE;
    if (!$DATABASE) return null;
    $data = $array ? ($data["token"] ?? $data["apikey"] ?? "miku") : $data;
    $stmt = $DATABASE->prepare("SELECT username FROM miku_users WHERE token = :token OR apikey = :token");
    $stmt->execute([':token' => $data]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return null;
    }
    return $row["username"];
}

/**
 * 在给定的字符串开头或末尾添加斜杠("/")，如果它尚未以斜杠结尾。
 *
 * @param string $inputString 要处理的字符串
 * @return string 处理后的字符串，确保以斜杠结尾
 */
function addSlashIfNeeded($inputString)
{
    if (substr($inputString, 0, 1) !== '/') {
        $inputString = '/' . $inputString;
    }
    if (substr($inputString, -1) !== '/') {
        $inputString .= '/';
    }
    return $inputString;
}

/**
 * 检查给定文件夹中是否存在指定的文件
 *
 * @param array $folders 文件夹路径的数组
 * @param string $file 要检查的文件名
 * @return array|bool 如果文件存在，则返回包含文件路径的数组；如果文件不存在，则返回false
 */
function check_files($floders, $file)
{
    $files = [];
    foreach ($floders as $floder) {
        if (file_exists($floder . $file)) {
            $files[] = $floder . $file;
        }
    }
    if (count($files) > 0) {
        return $files;
    }
    return False;
}

/**
 * 重新格式化参数为 HTML 表格
 *
 * @param array $type 请求方法数组
 * @param array $url 请求 URL 及信息数组
 * @return string 返回格式化后的 Markdown 表格
 */
function re_add($type = [], $url = [])
{
    $table = "<table><thead><tr><th>Method</th><th>URL</th><th>Info</th></tr></thead><tbody>";
    $info = array_values($url);
    $url = array_keys($url);
    for ($i = 0; $i < count($type); $i++) {
        $table .= "<tr><td>{$type[$i]}</td><td><a href='{$url[$i]}'>{$url[$i]}</a></td><td>" . (isset($info[$i]) ? $info[$i] : '') . "</td></tr>";
    };
    $table .= "</tbody></table>";
    return $table;
}


/**
 * 重新格式化参数为 Markdown 表格
 *
 * @param array $key 参数键和参数值数组
 * @return string 返回格式化后的 Markdown 表格
 */
function re_par($key = [])
{
    $table = "";
    if ($key != []) {
        $table = "<table><thead><tr><th>Key</th><th>Info</th></tr></thead><tbody>";
        parseTable('', $key, $table);
        $table .= "</tbody></table>";
    }
    return $table;
}

function parseTable($prefix, $key, &$table)
{
    foreach ($key as $k => $v) {
        if (is_array($v)) {
            parseTable($prefix . "{$k}.", $v, $table);
        } else {
            $table .= "<tr><td><code>{$prefix}{$k}</code></td><td>{$v}</td></tr>";
        }
    }
}


/**
 * 返回结果
 *
 * @param mixed $content 返回的数据内容
 * @param int $status 状态码，默认为 200
 * @return void
 */
function return_json($content, $status = 200)
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Expose-Headers: *');
    header('Access-Control-Max-Age: 3600');
    header("HTTP/1.1 $status");
    header('Content-type:text/json;charset=utf-8');
    die(json_encode(['status' => $status, 'data' => $content, 'time' => time()], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
/**
 * 检查 API 状态
 *
 * @return bool 返回是否需要处理 API 请求
 */
function handle_check($name)
{
    global $DATABASE;
    $query = $DATABASE->prepare("SELECT status FROM miku_api WHERE name = ?");
    $query->bindParam(1, $name, PDO::PARAM_STR);
    $query->execute();
    $status = $query->fetchColumn();
    if ($status == 'false') {
        return_json("This API already closed", 406);
    } else {
        return true;
    }
}

/**
 * 递归查询文件
 *
 * @param array $dirs 目录路径数组
 * @param string $file 需要查询的文件名，可选，默认为.php文件，支持末尾字查询
 * @param string $prefix 前缀，可选，默认为空字符串
 * @return array 返回文件的相对路径数组
 */
function find_files($dirs, $file = '.php', $prefix = '')
{
    $relative_paths = [];

    foreach ($dirs as $dir) {
        if (is_dir($dir)) {
            $subdirs = glob("$dir" . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR);
            $files = glob("$dir" . DIRECTORY_SEPARATOR . "*$file");

            foreach ($files as $filePath) {
                if (is_file($filePath)) {
                    $relativePath = str_replace(__ROOT_DIR__, '', $filePath);
                    $relativePath = ltrim($relativePath, DIRECTORY_SEPARATOR);
                    $relative_paths[] = $prefix . $relativePath;
                }
            }

            $subfiles = find_files($subdirs, $file, $prefix);
            $relative_paths = array_merge($relative_paths, $subfiles);
        }
    }

    return $relative_paths;
}


/**
 * 尝试将 JSON 字符串解析为数组，或将数组转换为 JSON 字符串
 *
 * @param string|array $json 要解析的 JSON 字符串或要转换的数组
 * @return array|string 返回解析后的数组或数组转换后的 JSON 字符串，如果解码和编码都失败则返回原始字符串
 */
function json($json)
{
    if (is_array($json)) {
        $encoded = json_encode((object)$json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($encoded !== false) {
            return $encoded;
        } else {
            return $json;
        }
    } else {
        $decoded = json_decode($json, true);
        if ($decoded !== null) {
            return $decoded;
        } else {
            return $json;
        }
    }
}
/**
 * 检查请求次数是否超过限制
 * 此函数应在 init() 内执行
 *
 * @param string $limit 最大请求次数和时间单位，例如 '3/10s', '10/min', '5/hour', '100/day'
 * @return void
 */
function RequestLimit($limit)
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? "Unknown";
    $currentTime = time();

    preg_match('/(\d+)\s*\/\s*(\w+)/', $limit, $matches);
    $quantity = intval($matches[1]);
    $unit = strtolower($matches[2]);

    switch ($unit) {
        case 's':
            $interval = $quantity;
            break;
        case 'min':
            $interval = $quantity * 60;
            break;
        case 'hour':
            $interval = $quantity * 60 * 60;
            break;
        case 'day':
            $interval = $quantity * 60 * 60 * 24;
            break;
        default:
            throw new Exception("Time units are only supported as s, min, hour, day");
    }

    $startTime = $currentTime - $interval;

    global $DATABASE;

    $url = addSlashIfNeeded(parse_url($_SERVER['REQUEST_URI'])["path"]) ?? "Unknown";
    $stmt = $DATABASE->prepare("SELECT COUNT(*) AS count FROM miku_access WHERE ip = :ip AND time >= :start_time AND time <= :end_time AND url = :url;");
    $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
    $stmt->bindValue(':start_time', date("Y-m-d H:i:s", $startTime), PDO::PARAM_INT);
    $stmt->bindValue(':end_time', date("Y-m-d H:i:s", $currentTime), PDO::PARAM_INT);
    $stmt->bindParam(':url', $url);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $count = $result['count'];

    $count = $count + 1;
    if ($count > $quantity && $ip !== "127.0.0.1" && $ip !== "::1") {
        return_json("Too Many Requests", 429);
    }
}

/**
 * 将请求记录到日志
 * @return void
 */
function AddAccess()
{
    global $DATABASE;
    $ip = $_SERVER["REMOTE_ADDR"] ?? "Unknown";
    $url = addSlashIfNeeded(parse_url($_SERVER['REQUEST_URI'])["path"]) ?? "Unknown";
    $referer = $_SERVER["HTTP_REFERER"] ?? "Unknown";
    $param = json($_REQUEST);
    try {
        $stmt = $DATABASE->prepare("INSERT INTO miku_access (ip, url, referer, param, method, agent) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $ip);
        $stmt->bindParam(2, $url);
        $stmt->bindParam(3, $referer);
        $stmt->bindParam(4, $param);
        $stmt->bindParam(5, $_SERVER["REQUEST_METHOD"]);
        $stmt->bindParam(6, $_SERVER['HTTP_USER_AGENT']);
        $stmt->execute();
    } catch (PDOException $e) {
        (new logger())->error("PDOException: ".$e->errorInfo[2]);
    }
}

/**
 * 获取路径数组相对于根路径的路径
 * @param array $paths 路径数组
 * @param string $root 根路径，默认为根目录
 * @return array 相对路径数组
 */
function getPath($paths, $root = NULL)
{
    $DIRECTORY_SEPARATOR = DIRECTORY_SEPARATOR;
    if (empty($root)) {
        $root = __ROOT_DIR__;
    }

    if (substr($root, -1) != $DIRECTORY_SEPARATOR) {
        $root .= $DIRECTORY_SEPARATOR;
    }

    $root = str_replace('/', $DIRECTORY_SEPARATOR, $root);

    $paths = array_map(function ($path) use ($DIRECTORY_SEPARATOR) {
        $path = str_replace('/', $DIRECTORY_SEPARATOR, $path);
        if ($DIRECTORY_SEPARATOR === '\\') {
            $path = str_replace('\\', '\\\\', $path);
        }
        return $path;
    }, $paths);

    return array_map(function ($path) use ($root) {
        $result = str_replace($root, '', $path);
        return ($result == '') ? '/' : $result;
    }, $paths);
}

/**
 * 根据输入的HTTP状态码返回对应的内容
 * @param int $code HTTP状态码
 * @return void 返回JSON格式的内容并结束脚本
 * @throws Exception 如果HTTP状态码不存在则抛出异常
 */
function code($code)
{
    $data = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    ];    
    if (array_key_exists($code, $data)) {
        return_json($data[$code], $code);
    } else {
        throw new Exception("Unknown HTTP Code.");
    }
}
