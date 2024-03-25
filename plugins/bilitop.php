<?php
class bilitop
{
    public const name = 'B站热门视频';
    public const version = '1.0';
    public const profile = '一键B站热门视频';
    public const method = 'GET';
    public const author = 'molanp';
    public const response = [
        "data" => [
            "name" => "视频名称",
            "url" => "视频链接"
        ]
    ];
    public function init()
    {
        $bilitop = (new requests())->get("https://api.bilibili.com/x/web-interface/popular")->json()["data"]["list"];
        $i = 0;
        $top = [];
        for ($i; $i < count($bilitop); $i++) {
            if (isset($bilitop[$i]["title"])) {
                $top[] = [
                    "rank" => count($top) + 1,
                    "name" => $bilitop[$i]["title"],
                    "url" => $bilitop[$i]["short_link_v2"]
                ];
            }
        };
        return_json($top);
    }
}
