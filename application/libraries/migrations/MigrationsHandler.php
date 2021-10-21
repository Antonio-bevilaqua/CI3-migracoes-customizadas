<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once(getcwd() . '/application/libraries/migrations/MigrationsInterface.php');

class MigrationsHandler
{
    private $MIGRATIONS_DIR = '';
    private $TABLE = "ci_migrations";
    private $CI = null;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->MIGRATIONS_DIR = getcwd() . '/application/migrations';
        $this->CI->load->dbforge();
    }

    private function createMigrationsTable()
    {
        if (!$this->CI->db->table_exists($this->TABLE)) {
            $fields = $this->migrationFields();
            $this->CI->dbforge->add_field($fields);
            $this->CI->dbforge->add_key('id', TRUE);
            $this->CI->dbforge->create_table($this->TABLE, TRUE);
        }
    }

    private function migrationFields()
    {
        return [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'migration_file' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ];
    }

    private function createHtaccess()
    {
        $content = "<Files>\n
            Order allow,deny\n
            Deny from all\n
        </Files>\n";
        file_put_contents($this->MIGRATIONS_DIR . '/.htaccess', $content);
    }

    private function createMigrationsDirectory()
    {
        if (!is_dir($this->MIGRATIONS_DIR)) {
            mkdir($this->MIGRATIONS_DIR, 0777);
            $this->createHtaccess();
        };
    }

    private function execUp(MigrationsInterface $migrationInstance, string $file)
    {
        $migrationInstance->up();

        $this->CI->db->insert($this->TABLE, [
            'migration_file' => $file
        ]);

        echo "Migration do arquivo " . $file . " executada.\r\n";
    }

    private function execFileUp($file)
    {
        if (file_exists($this->MIGRATIONS_DIR)) {
            try {
                include_once($this->MIGRATIONS_DIR . '/' . $file);

                $className = "migration_" . str_replace('.php', '', $file);

                $migrationInstance = new $className();

                $this->execUp($migrationInstance, $file);
            } catch (Exception $e) {
                echo "Erro: " . $e;
            }
        }
    }

    private function execDown(MigrationsInterface $migrationInstance, string $file)
    {
        $migrationInstance->down();

        echo "Migration do arquivo " . $file . " executada.\r\n";
    }

    private function execFileDown($file)
    {
        if (file_exists($this->MIGRATIONS_DIR)) {
            try {
                include_once($this->MIGRATIONS_DIR . '/' . $file);

                $className = "migration_" . str_replace('.php', '', $file);

                $migrationInstance = new $className();

                $this->execDown($migrationInstance, $file);
            } catch (Exception $e) {
                echo "Erro: " . $e;
            }
        }
    }

    public function migrate()
    {
        if (!is_dir($this->MIGRATIONS_DIR)) throw new Exception("Diretório de migrations não encontrado.");

        $files = scandir($this->MIGRATIONS_DIR);
        $files = array_diff($files, ['.', '..']);

        if (!empty($files)) {
            $this->createMigrationsTable();

            foreach ($files as $file) {
                $this->CI->db->select('id');
                $this->CI->db->from($this->TABLE);
                $this->CI->db->where('migration_file', $file);
                $hasRun =  $this->CI->db->get()->result();
                if ($hasRun) continue;

                $this->execFileUp($file);
            }
        }
    }

    public function rollback()
    {
        $this->CI->db->select('id, migration_file');
        $this->CI->db->from($this->TABLE);
        $this->CI->db->order_by('id', 'DESC');
        $migration =  $this->CI->db->get()->result();
        if (!$migration) throw new Exception("Nenhuma migration para dar rollback.");

        if (!file_exists($this->MIGRATIONS_DIR . '/' . $migration[0]->migration_file)) throw new Exception("Arquivo de migration não encontrado.");

        $this->execFileDown($migration[0]->migration_file);

        $this->CI->db->delete($this->TABLE, ['id' => $migration[0]->id]);
    }

    public function make_migration_file()
    {
        $this->createMigrationsDirectory();
        $timeNow = date('Ymd_His');
        $filename = $timeNow . '.php';
        $migrationInterface =  "getcwd().'/application/libraries/migrations/MigrationsInterface.php'";
        $migrationBase =  "getcwd().'/application/libraries/migrations/MigrationsBase.php'";
        $content = "<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once({$migrationInterface});
require_once({$migrationBase});

class migration_{$timeNow} extends MigrationsBase implements MigrationsInterface
{

    public function up()
    {
        //Seu conteúdo de entrada aqui
    }


    public function down()
    {
        //Seu conteúdo de saída aqui
    }
}
";
        if (file_put_contents($this->MIGRATIONS_DIR . '/' . $filename, $content)) {
            echo "Arquivo $filename criado com sucesso em {$this->MIGRATIONS_DIR} ";
        }
    }
}
