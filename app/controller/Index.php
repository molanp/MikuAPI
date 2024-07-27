<?php

namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;


class Index extends BaseController
{
    public function index()
    {
        try {
            $migrationExists = DB::table('migrations')
                ->where('migration_name', 'InstallMikuapi')
                ->exists();

            $view = $migrationExists ? 'index/index' : 'install/index';
        } catch (\Exception $e) {
            $view = 'install/index';
        }

        return View::fetch($view);
    }
}
