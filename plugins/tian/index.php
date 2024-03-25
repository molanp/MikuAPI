<?php
class tian
{
    public const name = '舔狗日记';
    public const version = '20231104';
    public const profile = '一键获取舔狗日记';
    public const method = 'GET';
    public const author = 'molanp';
    public const response = [
        "data" => "舔狗语录"
    ];
    public function init()
    {
        $data = json(file_get_contents(__DIR__ . "/tgrj.json"))["data"];
        return_json($data[array_rand($data)]);
    }
}
