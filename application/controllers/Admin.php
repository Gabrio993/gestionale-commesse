<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{
    public function index()
    {
        $this->richiedi_admin();

        $data = array(
            'nome_utente' => $this->session->userdata('utente_nome'),
            'email_utente' => $this->session->userdata('utente_email'),
        );

        $this->load->view('admin/dashboard', $data);
    }

    public function utenti()
    {
        $this->richiedi_admin();
        $this->load->model('Utente_model');

        $data['utenti'] = $this->Utente_model->tutti();
        $this->load->view('admin/utenti', $data);
    }
}
