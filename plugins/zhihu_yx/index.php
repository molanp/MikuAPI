<?php
class zhihu_yx
{
    public const name = '知乎盐选';
    public const version = '1.0';
    public const profile = '一键获取随机知乎盐选文章<br>支持搜索文章<br>文章版权归原作者所有<br>__仅供学习交流，严禁违法用途__';
    public const method = 'GET';
    public const author = 'molanp';
    public const request = [
        "id" => "获取指定id文章",
        "title" => "搜索相似标题文章"
    ];
    public const response = [
        "id" => "文章id",
        "title" => "文章标题",
        "content" => "文章内容",
        "createTime" => "文章保存时间",
    ];
    private function fetchArticleById($id)
    {
        $database = __DIR__ . "/zhihu.db";
        $db = new SQLite3($database);
        $stmt = $db->prepare("SELECT id, title, content, createTime FROM articles WHERE id = :id");
        $stmt->bindValue(":id", $id);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        $db->close();

        if ($result) {
            return [
                "code" => 200,
                "data" => [
                    "id" => $result["id"],
                    "title" => $result["title"],
                    "content" => $result["content"],
                    "createTime" => $result["createTime"]
                ]
            ];
        } else {
            return [
                "code" => 404,
                "data" => "Article not found"
            ];
        }
    }

    private function fetchRandomArticle()
    {
        $database = __DIR__ . "/zhihu.db";
        $db = new SQLite3($database);

        $maxIdResult = $db->querySingle("SELECT MAX(id) FROM articles");
        $maxId = $maxIdResult ? (int) $maxIdResult : 0;

        if ($maxId === 0) {
            return [
                "code" => 404,
                "data" => "No articles found"
            ];
        }

        $randomId = mt_rand(1, $maxId);

        $stmt = $db->prepare("SELECT id, title, content, createTime FROM articles WHERE id = :id");
        $stmt->bindValue(":id", $randomId);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        $db->close();

        if ($result) {
            return [
                "code" => 200,
                "data" => [
                    "id" => $result["id"],
                    "title" => $result["title"],
                    "content" => $result["content"],
                    "createTime" => $result["createTime"]
                ]
            ];
        } else {
            return [
                "code" => 404,
                "data" => "Article not found"
            ];
        }
    }




    private function fetchArticleByTitle($title)
    {
        $database = __DIR__ . "/zhihu.db";
        $db = new SQLite3($database);
        $stmt = $db->prepare("SELECT id, title, content, createTime FROM articles WHERE title LIKE :title");
        $stmt->bindValue(":title", "%$title%");
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        $db->close();

        if ($result) {
            return [
                "code" => 200,
                "data" => [
                    "id" => $result["id"],
                    "title" => $result["title"],
                    "content" => $result["content"],
                    "createTime" => $result["createTime"]
                ]
            ];
        } else {
            return [
                "code" => 404,
                "data" => "Article not found" // No articles found with the given title
            ];
        }
    }

    function init($get)
    {
        $id = $get["id"] ?? null;
        $title = $get["title"] ?? null;

        if ($id && $title) {
            return_json(["error" => "Cannot search by both id and title"], 400);
        }

        if ($id) {
            $result = $this->fetchArticleById($id);
        } elseif ($title) {
            $result = $this->fetchArticleByTitle($title);
        } else {
            $result = $this->fetchRandomArticle();
        }

        return_json($result["data"], $result["code"]);
    }
}
