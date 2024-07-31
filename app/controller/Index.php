<?php

namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;

function isInstall()
{
    try {
        if (DB::table('migrations')->where('migration_name', 'InstallMikuapi')->find()) {
            return true;
        };
    } catch (\Exception $e) {
        return false;
    }
}

class Index extends BaseController
{
    public function index()
    {
        if (isInstall()) {
            return View::fetch('index/index');
        }
        return View::fetch('install/index');
    }
}
