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

    protected function utente_loggato()
    {
        return (bool) $this->session->userdata('utente_id');
    }

    protected function ruolo_utente()
    {
        return (string) $this->session->userdata('utente_ruolo');
    }

    protected function richiedi_login()
    {
        if ( ! $this->utente_loggato())
        {
            redirect('/');
            exit;
        }
    }

    protected function richiedi_admin()
    {
        $this->richiedi_login();

        if ( ! in_array($this->ruolo_utente(), array('admin', 'superadmin'), true))
        {
            show_error('Accesso non autorizzato.', 403);
            exit;
        }
    }

    protected function richiedi_superadmin()
    {
        $this->richiedi_login();

        if ($this->ruolo_utente() !== 'superadmin')
        {
            show_error('Accesso non autorizzato.', 403);
            exit;
        }
    }
}
