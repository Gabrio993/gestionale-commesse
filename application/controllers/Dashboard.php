<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function index()
    {
        if ( ! $this->session->userdata('utente_id'))
        {
            redirect('/');
            return;
        }

        $data = array(
            'nome_utente' => $this->session->userdata('utente_nome'),
            'email_utente' => $this->session->userdata('utente_email'),
        );

        $this->load->view('auth/dashboard', $data);
    }
}
