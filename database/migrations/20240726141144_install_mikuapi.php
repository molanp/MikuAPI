<?php

use think\helper\Arr;
use think\migration\Migrator;
use think\migration\db\Column;

class InstallMikuapi extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('miku_data', array('engine' => 'MyISAM'));
        $table
            ->addColumn('idx', 'string', array('null' => true, 'limit' => 48))
            ->addColumn('content', 'text', array('null' => true))
            ->addColumn('des', 'text', array('null' => true))
            ->addColumn('belong', 'text', array('null' => true))
            ->addColumn('time', 'timestamp', array('default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'))
            ->create();

        $table = $this->table('miku_users', array('engine' => 'MyISAM'));
        $table
            ->addColumn('username', 'text', array('null' => true))
            ->addColumn('password', 'text', array('null' => true))
            ->addColumn('token', 'text', array('null' => true))
            ->addColumn('apikey', 'text', array('null' => true))
            ->addColumn('permission', 'integer', array('null' => true))
            ->addColumn('regtime', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
            ->addColumn('logtime', 'timestamp', array('default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'))
            ->create();

        $table = $this->table('miku_archives', array('engine' => 'MyISAM'));
        $table
            ->addColumn('title', 'text', array('null' => true))
            ->addColumn('content', 'text', array('null' => true))
            ->addColumn('author', 'text', array('null' => true))
            ->addColumn('type', 'text', array('null' => true))
            ->addColumn('date', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
            ->addColumn('modified', 'timestamp', array('default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'))
            ->create();

        $table = $this->table('miku_access', array('engine' => 'MyISAM'));
        $table
            ->addColumn('time', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
            ->addColumn('ip', 'text', array('null' => true))
            ->addColumn('url', 'text', array('null' => true))
            ->addColumn('method', 'text', array('limit' => 100, 'null' => true))
            ->addColumn('referer', 'text', array('limit' => 100, 'null' => true))
            ->addColumn('param', 'text', array('limit' => 200, 'null' => true))
            ->addColumn('agent', 'text', array('limit' => 100, 'null' => true))
            ->create();

        $table = $this->table('miku_api', array('engine' => 'MyISAM'));
        $table
            ->addColumn('name', 'text', array('null' => true))
            ->addColumn('version', 'text', array('null' => true))
            ->addColumn('author', 'text', array('null' => true))
            ->addColumn('method', 'text', array('null' => true))
            ->addColumn('profile', 'text', array('null' => true))
            ->addColumn('request', 'text', array('null' => true))
            ->addColumn('response', 'text', array('null' => true))
            ->addColumn('class', 'text', array('null' => true))
            ->addColumn('url_path', 'text', array('null' => true))
            ->addColumn('file_path', 'text', array('null' => true))
            ->addColumn('type', 'text', array('null' => true))
            ->addColumn('top', 'text', array('null' => true))
            ->addColumn('status', 'text', array('null' => true))
            ->addColumn('time', 'timestamp', array('default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'))
            ->create();
    }
}
