<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MigrationsBase
{
    private $CI = null;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->dbforge();
    }

    public function create_table(string $table, array $data, string $primaryKey = 'id')
    {
        $this->CI->dbforge->add_field($data);
        $this->CI->dbforge->add_key($primaryKey, TRUE);
        $this->CI->dbforge->create_table($table, TRUE);
    }

    public function rename_table(string $table, string $new_name)
    {
        $this->CI->dbforge->rename_table($table, $new_name);
    }

    public function add_columns(string $table, array $data)
    {
        $this->CI->dbforge->add_column($table, $data);
    }

    public function change_columns(string $table, array $data)
    {
        $this->CI->dbforge->modify_column($table, $data);
    }

    public function drop_table(string $table)
    {
        $this->CI->dbforge->drop_table($table);
    }

    public function drop_columns(string $table, array $data)
    {
        foreach ($data as $field) {
            $this->CI->dbforge->drop_column($table, $field);
        }
    }
}
