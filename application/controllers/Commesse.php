<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Commesse extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('Commessa_model');
        $this->load->model('Cliente_model');
        $this->load->model('Registrazione_ore_model');
    }

    public function index()
    {
        $this->richiedi_login();

        $data['commesse'] = $this->Commessa_model->tutte(true);
        $this->load->view('commesse/index', $data);
    }

    public function dettaglio($id)
    {
        $this->richiedi_login();

        $commessa = $this->Commessa_model->trova($id);
        if ( ! $commessa)
        {
            show_404();
            return;
        }

        $ruolo = $this->ruolo_utente();
        $ore_commessa = in_array($ruolo, array('admin', 'superadmin'), true)
            ? $this->Registrazione_ore_model->per_commessa($id)
            : $this->Registrazione_ore_model->per_commessa_e_utente($id, $this->session->userdata('utente_id'));

        $data = array(
            'commessa' => $commessa,
            'ore_commessa' => $ore_commessa,
            'puo_modificare' => true,
            'ruolo_utente' => $ruolo,
        );

        $this->load->view('commesse/dettaglio', $data);
    }

    public function nuova()
    {
        $this->richiedi_admin();

        $data['clienti'] = $this->Cliente_model->tutti(true);
        $this->load->view('commesse/nuova', $data);
    }

    public function salva()
    {
        $this->richiedi_admin();

        if ($this->input->method(TRUE) !== 'POST')
        {
            show_error('Metodo non consentito.', 405);
            return;
        }

        $this->form_validation->set_rules('cliente_id', 'Cliente', 'required|integer');
        $this->form_validation->set_rules('codice', 'Codice', 'trim|max_length[50]');
        $this->form_validation->set_rules('attivita', 'Attività', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('descrizione', 'Descrizione', 'trim');

        if ($this->form_validation->run() === false)
        {
            $data['clienti'] = $this->Cliente_model->tutti(true);
            $this->load->view('commesse/nuova', $data);
            return;
        }

        $attivita = trim((string) $this->input->post('attivita', true));

        $this->Commessa_model->crea(array(
            'cliente_id' => (int) $this->input->post('cliente_id', true),
            'codice' => trim((string) $this->input->post('codice', true)) ?: null,
            'attivita' => $attivita,
            'nome' => $attivita,
            'descrizione' => trim((string) $this->input->post('descrizione', true)) ?: null,
            'stato' => 'attiva',
            'attivo' => 1,
        ));

        redirect('commesse');
    }
}
