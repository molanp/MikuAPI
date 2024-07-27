<?php

namespace app\controller;

use think\facade\Db;
use think\facade\View;
use think\facade\Config;
use think\facade\Console;

class Install
{
    public function index()
    {
        try {
            if (DB::table('migrations')->where('migration_name', 'InstallMikuapi')->exists()) {
                return json([
                    'status' => 404,
                    'data' => 'Not Found',
                    'time' => time()
                ], 404);
            }
        } catch (\Exception $e) {/// 不能这样判断！！！！！！！
            /// 此处判断不完善，需要修改
            $config = Config::get('database');
            $config['connections']['production'] = array_merge(
                $config['connections']['production'],
                [
                    'hostname' => $_POST['hostname'],
                    'hostport' => $_POST['hostport'],
                    'database' => $_POST['database'],
                    'username' => $_POST['username'],
                    'password' => $_POST['password'] ?? '',
                ]
            );
            file_put_contents(app()->getConfigPath() . 'database.php', '<?php return ' . var_export($config, true) . ';');
            Config::load(app()->getConfigPath() . 'database.php', 'database');

            Console::call('migrate:run', []);

            Db::name('miku_archives')->insert([
                'title' => '欢迎使用MikuAPI',
                'content' => '这是系统默认生成的公告，你可以<code>删除</code>,<code>编辑</code>它',
                'author' => $_POST["usr"],
                'type' => 'announce',
            ]);

            Db::name('miku_users')->insert([
                'username' => $_POST['usr'],
                'password' => password_hash($_POST['pwd'], PASSWORD_DEFAULT),
                'permission' => 9
            ]);

            return View::fetch('install/success');
        } catch (\Exception $e) {
            return View::fetch('install/error', [
                'err' => $e->getMessage()
            ]);
        }
    }
}
