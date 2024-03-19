<?php
namespace gpt_url;
class PluginMeta
{
    public const name = 'GPT 镜像';
    public const version = '1.0';
    public const profile = '一键获取最新可用的gpt镜像网站';
    public const method = 'GET';
    public const author = 'molanp';
    public const response = [
        'count' => 'gpt镜像url数量',
        'default' => '可用gpt镜像url列表'
    ];
}
class PluginHandler
{
    public function init()
    {
        $default = [];
        $html = (new requests())->get('https://c.aalib.net/tool/chatgpt/')->content();
        preg_match_all('/<td><a\s+href="([^"]+)"\s+target="_blank">([^<]+)<\/a><\/td>/', $html, $matches);
        if (count($matches[1]) > 0) {
            foreach ($matches[1] as $index => $link) {
                $default[] = $link;
            }
        }
        _return_(['count' => count($default), 'default' => array_values(array_unique($default))]);
    }
}
