<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'security'));
        $this->load->model('Utente_model');
    }

    public function index()
    {
        if ($this->session->userdata('utente_id'))
        {
            redirect('dashboard');
            return;
        }

        $this->load->view('auth/landing');
    }

    public function login()
    {
        if ($this->session->userdata('utente_id'))
        {
            redirect('dashboard');
            return;
        }

        $this->load->view('auth/login');
    }

    public function registrati()
    {
        if ($this->session->userdata('utente_id'))
        {
            redirect('dashboard');
            return;
        }

        $this->load->view('auth/registrazione');
    }

    public function salva_registrazione()
    {
        if ($this->session->userdata('utente_id'))
        {
            redirect('dashboard');
            return;
        }

        $this->form_validation->set_rules('nome', 'Nome', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('cognome', 'Cognome', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[191]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('conferma_password', 'Conferma password', 'required|matches[password]');

        if ($this->form_validation->run() === false)
        {
            $this->load->view('auth/registrazione');
            return;
        }

        $email = strtolower(trim($this->input->post('email', true)));
        if ($this->Utente_model->trova_per_email($email))
        {
            $data['errore'] = 'Questa email è già registrata.';
            $this->load->view('auth/registrazione', $data);
            return;
        }

        $dati = array(
            'nome' => trim($this->input->post('nome', true)),
            'cognome' => trim($this->input->post('cognome', true)),
            'email' => $email,
            'password_hash' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
            'ruolo' => 'utente',
            'attivo' => 1,
        );

        $this->Utente_model->crea($dati);

        $data['messaggio'] = 'Registrazione completata. Ora puoi effettuare il login.';
        $this->load->view('auth/login', $data);
    }

    public function autentica()
    {
        if ($this->session->userdata('utente_id'))
        {
            redirect('dashboard');
            return;
        }

        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === false)
        {
            $this->load->view('auth/login');
            return;
        }

        $email = strtolower(trim($this->input->post('email', true)));
        $password = $this->input->post('password', true);
        $utente = $this->Utente_model->trova_per_email($email);

        if ( ! $utente || ! (int) $utente->attivo || ! password_verify($password, $utente->password_hash))
        {
            $data['errore'] = 'Credenziali non valide.';
            $this->load->view('auth/login', $data);
            return;
        }

        $this->session->set_userdata(array(
            'utente_id' => $utente->id,
            'utente_nome' => trim($utente->nome . ' ' . $utente->cognome),
            'utente_email' => $utente->email,
            'utente_ruolo' => $utente->ruolo,
        ));

        redirect('dashboard');
    }

    public function logout()
    {
        $this->session->unset_userdata(array(
            'utente_id',
            'utente_nome',
            'utente_email',
            'utente_ruolo',
        ));

        $this->session->sess_destroy();
        redirect('/');
    }
}
