<?php
class logger
{
    private $file;

    function __construct()
    {
        if (!file_exists(__ROOT_DIR__ . '/logs')) {
            mkdir(__ROOT_DIR__ . '/logs');
        };
        $file = __ROOT_DIR__ . '/logs/' . date("Y-m-d") . '.log';
        $this->file = $file;
    }

    function info($str)
    {
        $this->write("info", $str);
    }

    function warn($str)
    {
        $this->write("warn", $str);
    }

    function debug($str)
    {
        $this->write("debug", $str);
    }

    function error($str)
    {
        $this->write("error", $str);
    }

    private function write($level, $msg)
    {
        $level = strtoupper($level);
        $file = fopen($this->file, 'a');
        if (!$file) throw new Exception('写入文件失败，请赋予 ' . $file . ' 文件写权限！');
        $user = get_name($_REQUEST, true) ?? "UNKNOWN_USER";
        $str = date("Y-m-d H:i:s") . " [$level] > [USER $user] $msg\n";
        fwrite($file, $str);
        fclose($file);
    }
}
