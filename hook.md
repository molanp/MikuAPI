# 插件规范

## 必须参数

|项|默认值|
|-|-|
|`name`|NULL|

## 非必须参数

|项|默认值|说明|
|:-:|:-:|:-:|
|`version`|1.0|插件版本|
|`author`|Unknown|插件作者|
|`method`|ANY|允许的请求方法|
|`profile`||插件简介|
|`request`|[]|数组化的请求参数|
|`response`|[]|数组化的响应参数|
|`type`|一些工具|插件分类|

!> 插件类名必须与插件文件名或外部文件夹名称相同<br>否则该插件不会被加载并记入日志

!> 自定义用户插件建议放入`extensive_plugins`文件夹<br>`plugins`文件夹内文件每次更新会被删除

## Demo

### 最佳实例

```php
<?php
# name_generator.php
class name_generator
{
    public const name = '亚文化取名机';
    public const version = '1.0';
    public const profile = '一键生成最潮的亚文化网名';
    public const method = 'ANY';
    public const author = 'molanp';
    public const response = [
        "data" => "你的亚文化名字"
    ];
    public function init()
    {
        $el1 = ["废墟", ..., "无聊"];
        $el2 = ["之", ""];
        $el3 = ["小丑", ..., "放射性"];
        $el4 = ["天使",...,"糖"];
        _return_($el1[array_rand($el1)] . $el2[array_rand($el2)] . $el3[array_rand($el3)] . $el4[array_rand($el4)]);
    }
}
```

### 最小实例

```php
<?php
# name_generator.php
class name_generator
{
    const name = '亚文化取名机';
    function init()
    {
        $el1 = ["废墟", ..., "无聊"];
        $el2 = ["之", ""];
        $el3 = ["小丑", ..., "放射性"];
        $el4 = ["天使",...,"糖"];
        _return_($el1[array_rand($el1)] . $el2[array_rand($el2)] . $el3[array_rand($el3)] . $el4[array_rand($el4)]);
    }
}
```

### 插件包实例

```php
<?php
#   demo
#   |---index.php
#   |---demo2.php

#index.php
class demo
{
    public const name = 'demo1';
    public function init()
    {
        ...
        _return_(1);
    }
}

#demo2.php
class demo2
{
    public const name = 'demo2';
    public function init()
    {
        ...
        _return_(2);
    }
}
```

# 部分函数说明

## \_return_

`_return_($content, $status=200, $location=false)`

返回结果(die)

@param mixed $content 返回的数据内容<br>
@param int $status 状态码，默认为 200<br>
@param bool|string $location 是否重定向，如果不为false，则重定向到$content内的链接<br>
@return void

### 示例

```php
<?php
_return_("test", 114514);
```

```json
{
    "status": 114514,
    "data": "test",
    "time": 1700000000
}
```

## jump

`jump($url)`

跳转到某一链接(die)

@param string  $url 链接<br>
@return void

## RequestLimit

`RequestLimit($limit)`

检查请求次数是否超过限制<br>
此函数应在 init() 内执行

@param string $limit 最大请求次数和时间单位，例如 '3/s', '10/min', '5/hour', '100/day'<br>
@return void

### 示例

```php
<?php
...
public function init() {
    RequestLimit("1/hour");
    ...
    _return_("OK");
}
```

## json

`json($json)`

尝试将 JSON 字符串解析为数组，或将数组转换为 JSON 字符串

@param string|array $json 要解析的 JSON 字符串或要转换的数组<br>
@return array|string 返回解析后的数组或数组转换后的 JSON 字符串，如果解码和编码都失败则返回原始字符串
