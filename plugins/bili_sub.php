<?php
namespace bili_sub;
class PluginMeta
{
    public const name = "bilibili视频解析";
    public const version = "1.0";
    public const profile = "获取b站视频基本信息";
    public const method = "GET";
    public const author = "molanp";
    public const request = ["url" => "视频链接"];
    public const response = [
        "avid" => "视频AV号",
        "title" => "视频标题",
        "cover" => "视频封面",
        "pubdate" => "稿件发布时间",
        "desc" => "视频简介",
        "owner" => [
            "mid" => "UP主uid",
            "name" => "UP主昵称",
            "face" => "UP主头像"
        ],
        "status" => [
            "view" => "播放数",
            "danmaku" => "弹幕数",
            "reply" => "评论数",
            "favorite" => "收藏数",
            "coin" => "投币数",
            "share" => "分享数",
            "now_rank" => "当前排名",
            "his_rank" => "历史最高排行",
            "like" => "获赞数"
        ]
    ];
}
class PluginHandler
{
    public function init($request)
    {
        if (isset($request["url"])) {
            preg_match("/BV(\w+)/", $request["url"], $matches);
            if (isset($matches[1])) {
                $json = (new requests())->get("https://api.bilibili.com/x/web-interface/view", ["bvid" => "BV" . $matches[1]])->json();
                if ($json["message"] == 0) {
                    $json = $json["data"];
                    $data = [
                        [
                            "avid" => "av" . $json["aid"],
                            "title" => $json["title"],
                            "cover" => $json["pic"],
                            "pubdate" => $json["pubdate"],
                            "desc" => $json["desc"],
                            "owner" => $json["owner"],
                            "status" => [
                                "view" => $json["stat"]["view"],
                                "danmaku" => $json["stat"]["danmaku"],
                                "reply" => $json["stat"]["reply"],
                                "favorite" => $json["stat"]["favorite"],
                                "coin" => $json["stat"]["coin"],
                                "share" => $json["stat"]["share"],
                                "now_rank" => $json["stat"]["now_rank"],
                                "his_rank" => $json["stat"]["his_rank"],
                                "like" => $json["stat"]["like"]
                            ]
                        ],
                        200
                    ];
                } else {
                    $data = [$json["message"], $json["code"]];
                }
            } else {
                $data = ["无效的BV号", 400];
            }
        } else {
            $data = ["无效的链接", 400];
        };
        _return_($data[0], $data[1]);
    }
}
