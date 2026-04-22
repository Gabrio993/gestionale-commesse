<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function index()
    {
        $this->richiedi_login();

        if ($this->ruolo_utente() === 'superadmin')
        {
            redirect('superadmin');
            return;
        }

        if ($this->ruolo_utente() === 'admin')
        {
            redirect('admin');
            return;
        }

        $data = array(
            'nome_utente' => $this->session->userdata('utente_nome'),
            'email_utente' => $this->session->userdata('utente_email'),
            'ruolo_utente' => $this->session->userdata('utente_ruolo'),
        );

        $this->load->view('auth/dashboard', $data);
    }
}
