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
        // La sessione serve in quasi tutto il progetto per sapere chi è loggato e con quale ruolo.
        $this->load->library('session');
    }

    protected function utente_loggato()
    {
        // La presenza di utente_id in sessione è il controllo più semplice per capire se esiste una login valida.
        return (bool) $this->session->userdata('utente_id');
    }

    protected function ruolo_utente()
    {
        // Il ruolo viene salvato in sessione dopo il login e guida tutti i controlli di accesso.
        return (string) $this->session->userdata('utente_ruolo');
    }

    protected function richiedi_login()
    {
        // Se non c'è una sessione valida, riportiamo subito alla landing.
        if ( ! $this->utente_loggato())
        {
            redirect('/');
            exit;
        }
    }

    protected function richiedi_admin()
    {
        // L'area admin è aperta ad admin e superadmin, ma non agli utenti normali.
        $this->richiedi_login();

        if ( ! in_array($this->ruolo_utente(), array('admin', 'superadmin'), true))
        {
            show_error('Accesso non autorizzato.', 403);
            exit;
        }
    }

    protected function richiedi_superadmin()
    {
        // Il superadmin è il livello più alto: può gestire anche i ruoli degli utenti.
        $this->richiedi_login();

        if ($this->ruolo_utente() !== 'superadmin')
        {
            show_error('Accesso non autorizzato.', 403);
            exit;
        }
    }
}
