<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Base Model per tutti i modelli CI3.
 *
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 */
class MY_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
}
