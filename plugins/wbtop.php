<?php
class wbtop
{
    public const name = '微博热搜';
    public const version = '1.0';
    public const profile = '一键获取微博热搜';
    public const method = 'GET';
    public const author = 'molanp';
    public const response = [
        "rank" => "热搜排行",
        "hot_word_num" => "热度",
        "category" => "所属分类",
        "hot_word" => "热搜",
        "url" => "话题链接"
    ];
    public function init()
    {
        $wbtop = (new requests())->get("https://weibo.com/ajax/side/hotSearch")->json()["data"]["realtime"];
        $i = 0;
        $top = [];
        for ($i; $i < count($wbtop); $i++) {
            if (isset($wbtop[$i]["category"])) {
                $top[] = [
                    "rank" => count($top) + 1,
                    "hot_word_num" => $wbtop[$i]["num"],
                    "category" => $wbtop[$i]["category"],
                    "hot_word" => $wbtop[$i]["note"],
                    "url" => "https://s.weibo.com/weibo?q=%23{$wbtop[$i]["word"]}%23"
                ];
            }
        };
        _return_($top);
    }
}
