<?php
namespace mrfd;
class PluginMeta
{
    public const name = '每日发癫语录';
    public const version = '1.0';
    public const profile = '每日一句发癫语录';
    public const method = 'GET';
    public const author = 'molanp';
    public const request = [
        "*name" => "需要发癫的对象"
    ];
}
class PluginHandler
{
    public function init($r)
    {
        $d = json(file_get_contents(__DIR__ . "/data.json"))["main"];
        if (!isset($r["name"])) {
            $result = "对空气发癫？";
        } else {
            $result = str_replace(
                "<name>",
                $r["name"],
                $d[array_rand($d)]
            );
        };
        _return_($result);
    }
}
