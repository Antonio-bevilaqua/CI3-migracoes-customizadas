<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(getcwd() . '/application/libraries/migrations/MigrationsInterface.php');
require_once(getcwd() . '/application/libraries/migrations/MigrationsBase.php');

class migration_20211021_221110 extends MigrationsBase implements MigrationsInterface
{

    public function up()
    {
        $this->create_table('usuarios', [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => "100",
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => "100",
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);
    }


    public function down()
    {
        $this->drop_table('usuarios');
    }
}
