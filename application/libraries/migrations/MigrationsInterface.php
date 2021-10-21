<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* TODA MIGRATION DEVE IMPLEMENTAR ESSA INTERFACE */
interface MigrationsInterface
{
    /* ENTRADA DA MIGRATION */
    public function up();

    /* SAÍDA DA MIGRATION */
    public function down();
}