<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Questo controller gestisce tutto il flusso di accesso: landing, login, registrazione e logout.
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'security'));
        $this->load->model('Utente_model');
    }

    public function index()
    {
        // Se l'utente è già autenticato, non mostriamo la landing: lo mandiamo direttamente nell'area giusta.
        if ($this->utente_loggato())
        {
            $this->redirect_post_login();
            return;
        }

        $this->load->view('auth/landing');
    }

    public function login()
    {
        if ($this->utente_loggato())
        {
            $this->redirect_post_login();
            return;
        }

        $this->load->view('auth/login');
    }

    public function registrati()
    {
        if ($this->utente_loggato())
        {
            $this->redirect_post_login();
            return;
        }

        $this->load->view('auth/registrazione');
    }

    public function salva_registrazione()
    {
        if ($this->utente_loggato())
        {
            $this->redirect_post_login();
            return;
        }

        // Regole minime per poter creare un account pulito.
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

        // La mail è univoca: se esiste già, blocchiamo la registrazione.
        if ($this->Utente_model->trova_per_email($email))
        {
            $data['errore'] = 'Questa email Ã¨ giÃ  registrata.';
            $this->load->view('auth/registrazione', $data);
            return;
        }

        // La password viene salvata solo in forma hashata, mai in chiaro.
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
        if ($this->utente_loggato())
        {
            $this->redirect_post_login();
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

        // Il login è valido solo se l'utente esiste, è attivo e la password coincide.
        if ( ! $utente || ! (int) $utente->attivo || ! password_verify($password, $utente->password_hash))
        {
            $data['errore'] = 'Credenziali non valide.';
            $this->load->view('auth/login', $data);
            return;
        }

        // In sessione salviamo solo i dati utili al resto dell'applicazione.
        $this->session->set_userdata(array(
            'utente_id' => $utente->id,
            'utente_nome' => trim($utente->nome . ' ' . $utente->cognome),
            'utente_email' => $utente->email,
            'utente_ruolo' => $utente->ruolo,
        ));

        $this->redirect_post_login();
    }

    public function cambia_password()
    {
        $this->richiedi_login();

        $data = array(
            'nome_utente' => $this->session->userdata('utente_nome'),
            'email_utente' => $this->session->userdata('utente_email'),
            'ruolo_utente' => $this->session->userdata('utente_ruolo'),
        );

        $this->load->view('auth/cambia_password', $data);
    }

    public function salva_password()
    {
        $this->richiedi_login();

        $this->form_validation->set_rules('password_attuale', 'Password attuale', 'required');
        $this->form_validation->set_rules('password_nuova', 'Nuova password', 'required|min_length[6]');
        $this->form_validation->set_rules('conferma_password_nuova', 'Conferma password', 'required|matches[password_nuova]');

        if ($this->form_validation->run() === false)
        {
            $data = array(
                'nome_utente' => $this->session->userdata('utente_nome'),
                'email_utente' => $this->session->userdata('utente_email'),
                'ruolo_utente' => $this->session->userdata('utente_ruolo'),
            );

            $this->load->view('auth/cambia_password', $data);
            return;
        }

        $utente = $this->Utente_model->trova_per_id((int) $this->session->userdata('utente_id'));
        if ( ! $utente || ! password_verify($this->input->post('password_attuale', true), $utente->password_hash))
        {
            $data = array(
                'nome_utente' => $this->session->userdata('utente_nome'),
                'email_utente' => $this->session->userdata('utente_email'),
                'ruolo_utente' => $this->session->userdata('utente_ruolo'),
                'errore' => 'La password attuale non è corretta.',
            );

            $this->load->view('auth/cambia_password', $data);
            return;
        }

        $nuovo_hash = password_hash($this->input->post('password_nuova', true), PASSWORD_DEFAULT);
        $this->Utente_model->aggiorna_password((int) $utente->id, $nuovo_hash);

        $this->session->set_flashdata('notice_success', 'Password aggiornata correttamente.');
        $this->redirect_post_login();
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

    private function redirect_post_login()
    {
        // Ogni ruolo viene portato nella sua area naturale.
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

        redirect('dashboard');
    }
}
