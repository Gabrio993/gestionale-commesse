<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH.'controllers/Admin.php';

class Superadmin extends Admin
{
    public function index()
    {
        $this->richiedi_superadmin();

        $data = array(
            'nome_utente' => $this->session->userdata('utente_nome'),
            'email_utente' => $this->session->userdata('utente_email'),
        );

        $this->load->view('superadmin/dashboard', $data);
    }

    public function utenti()
    {
        $this->richiedi_superadmin();
        $this->load->model('Utente_model');

        $data['utenti'] = $this->Utente_model->tutti();
        $this->load->view('superadmin/utenti', $data);
    }

    public function ruoli()
    {
        $this->utenti();
    }

    public function cambia_ruolo()
    {
        $this->richiedi_superadmin();
        if ($this->input->method(TRUE) !== 'POST')
        {
            show_error('Metodo non consentito.', 405);
            return;
        }
        $this->load->library('form_validation');
        $this->load->model('Utente_model');

        $this->form_validation->set_rules('utente_id', 'Utente', 'required|integer');
        $this->form_validation->set_rules('ruolo', 'Ruolo', 'required|in_list[utente,admin,superadmin]');

        if ($this->form_validation->run() === false)
        {
            redirect('superadmin/utenti');
            return;
        }

        $utente_id = (int) $this->input->post('utente_id', true);
        $ruolo = $this->input->post('ruolo', true);

        $this->Utente_model->aggiorna_ruolo($utente_id, $ruolo);

        redirect('superadmin/utenti');
    }
}
