<?php
if (file_exists(__CORE_DIR__ . '/install.lock')) { ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
        <meta name="renderer" content="webkit" />
        <meta name="force-rendering" content="webkit" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="Shortcut Icon" href="/favicon.ico">
        <link rel="bookmark" href="/favicon.ico" type="image/x-icon" />
        <title>安装MikuAPI</title>
        <script src="/assets/js/init.js"></script>
        <script src="https://registry.npmmirror.com/mdui/2.1.0/files/mdui.global.js"></script>
    </head>

    <body>
        <div class="grid">
            <div></div>
            <mdui-card>
                <h3>请勿重复安装</h3>
                <p>如需重复安装，请删除Core目录下的install.lock文件和数据库内容，然后刷新该页面</p>
                <mdui-button href="/">进入网站</mdui-button>
                <mdui-button href="/miku">管理员登录</mdui-button>
            </mdui-card>
            <div></div>
        </div>
    </body>

    </html>

<?php
    exit;
}

include_once __TEMPLATE_DIR__ . '/Install/index.html';
