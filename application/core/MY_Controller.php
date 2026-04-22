<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Base Controller per tutti i controller CI3.
 * Include DocBlock per autocomplete Intelephense.
 *
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property CI_Session $session
 * @property CI_Email $email
 * @property CI_Form_validation $form_validation
 * @property CI_Output $output
 * @property CI_Config $config
 * @property CI_URI $uri
 * @property CI_Router $router
 * @property CI_Security $security
 */
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }
}
