<?php
class what_eat
{
    private $what_eat;
    public const name = '今天吃什么';
    public const version = '1.0';
    public const profile = '一键获取今天要吃的菜名';
    public const method = 'GET';
    public const author = 'molanp';
    public function __construct()
    {
        $this->what_eat = [
            "肯德基",
            "麦当劳",
            "汉堡王",
            "华莱士",
            "螺蛳粉",
            "披萨",
            "方便面",
            "鸡排",
            "一点点",
            "赤坂亭",
            "战斧牛排",
            "洋芋",
            "蜜雪冰城",
            "烤冷面",
            "手抓饼",
            "食堂",
            "火锅",
            "海底捞",
            "萨莉亚",
            "沙县小吃",
            "凉白开",
            "麻辣香锅",
            "西红柿炒鸡蛋",
            "地三鲜",
            "水煮肉片",
            "回锅肉",
            "糖醋里脊",
            "葱煎豆腐",
            "鱼香肉丝",
            "糖醋里脊",
            "辣椒炒肉",
            "拍黄瓜",
            "香干肉丝",
            "清蒸生蚝",
            "孜然牛肉",
            "香菜牛肉",
            "小炒肉",
            "洋葱炒肉",
            "酸辣土豆丝",
            "醋溜土豆丝",
            "可乐鸡翅",
            "酱牛肉",
            "过桥米线",
            "清蒸鲈鱼",
            "清蒸桂鱼",
            "咕噜肉",
            "小酥肉",
            "锅包肉",
            "粉蒸肉",
            "红烧肉",
            "剁椒鱼头",
            "红烧茄子",
            "粉蒸排骨",
            "梅菜扣肉",
            "土豆炖牛肉",
            "土豆炖牛腩",
            "水煮蛋",
            "麦片",
            "白米饭",
            "蛋炒饭",
            "扬州炒饭",
            "炒方便面",
            "炸酱面",
            "刀削面",
            "饺子",
            "馄饨",
            "甜酒丸子",
            "凉面",
            "凉粉",
            "凉皮",
            "酸辣粉",
            "韭菜盒子",
            "土家酱香饼",
            "手抓饼",
            "油条",
            "皮蛋瘦肉粥",
            "紫菜汤",
            "玉带虾仁",
            "油发豆莛",
            "红扒鱼翅",
            "白扒通天翅",
            "孔府一品锅",
            "花揽桂鱼",
            "纸包鸡",
            "焖大虾",
            "锅烧鸡",
            "山东菜丸",
            "麻婆豆腐",
            "辣子鸡丁",
            "东坡肘子",
            "豆瓣鲫鱼",
            "口袋豆腐",
            "酸菜鱼",
            "夫妻肺片",
            "蚂蚁上树",
            "叫花鸡",
            "鱼香肉丝",
            "咸菜焖猪肉",
            "酿茄子",
            "酿豆腐",
            "梅菜扣肉",
            "客家盐焗鸡",
            "广式烧填鸭",
            "烧鹅",
            "红槽排骨",
            "豆豉蒸排骨",
            "煎酿三宝",
            "佛跳墙",
            "醉排骨",
            "荔枝肉",
            "扳指干贝",
            "尤溪卜鸭",
            "七星鱼丸汤",
            "软溜珠廉鱼",
            "龙身凤尾虾",
            "油爆双脆",
            "清炖全鸡",
            "烤方",
            "金陵丸子",
            "白汁圆菜",
            "清炖蟹粉狮子头",
            "水晶肴蹄",
            "鸡汤煮干丝",
            "凤尾虾",
            "三套鸭",
            "无锡肉骨头",
            "陆稿荐酱猪头肉",
            "西湖醋鱼",
            "东坡肉",
            "荷叶粉蒸肉",
            "西湖莼菜汤",
            "龙井虾仁",
            "虎跑素火腿",
            "香酥焖肉",
            "虾爆鳝背",
            "油焖春笋",
            "海参盆蒸",
            "腊味合蒸",
            "走油豆豉扣肉",
            "麻辣子鸡",
            "冰糖湘莲",
            "臭豆腐",
            "红烧寒菌",
            "炒血鸭",
            "湘西酸肉",
            "蝴蝶飘海",
            "清炖马蹄鳖",
            "黄山炖鸽",
            "徽州毛豆腐",
            "鱼咬羊",
            "香炸琵琶虾",
            "八大锤",
            "毛峰熏鲥鱼",
            "香菇盒",
            "火烘鱼",
            "蟹黄虾盅",
            "锅包肉",
            "猪肉炖粉条",
            "小鸡炖蘑菇",
            "韭菜干丝",
            "溜肥肠",
            "东北乱炖",
            "酸菜排骨",
            "芹菜鱼丝"
        ];
    }

    public function init()
    {
        $what_eat = $this->what_eat;
        return_json($what_eat[array_rand($what_eat)]);
    }
}
