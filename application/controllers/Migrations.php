<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migrations extends CI_Controller
{
    private $pass = "";
    private $auth = false;
    public function __construct()
    {
        parent::__construct();

        if (!$this->input->is_cli_request()) {
            $message_403 = "Essa página não pode ser acessada";
            show_error($message_403, 403);
            exit();
        }
    }

    private function authenticable($senha)
    {
        if (!$this->auth) return true;
        return ($senha !== $this->pass);
    }

    private function checkSenha($senha)
    {
        if (!$this->authenticable($senha)) throw new Exception("Proibido");
    }

    public function migrate($senha = '')
    {
        $this->checkSenha($senha);

        $this->load->library('migrations/MigrationsHandler.php', null, 'migrations');
        $this->migrations->migrate();
    }

    public function make($senha = '')
    {
        $this->checkSenha($senha);

        $this->load->library('migrations/MigrationsHandler.php', null, 'migrations');
        $this->migrations->make_migration_file();
    }

    public function rollback($senha = '')
    {
        $this->checkSenha($senha);

        $this->load->library('migrations/MigrationsHandler.php', null, 'migrations');
        $this->migrations->rollback();
    }
}
