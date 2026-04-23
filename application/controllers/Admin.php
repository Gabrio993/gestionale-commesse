<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('Utente_model');
        $this->load->model('Commessa_model');
    }

    /**
     * Genera una password temporanea semplice da leggere e sicura abbastanza
     * per un reset manuale interno.
     */
    protected function genera_password_temporanea()
    {
        return 'Tmp-' . strtoupper(bin2hex(random_bytes(4)));
    }

    public function index()
    {
        // L'area admin è visibile sia agli admin sia ai superadmin.
        $this->richiedi_admin();

        $data = array(
            'nome_utente' => $this->session->userdata('utente_nome'),
            'email_utente' => $this->session->userdata('utente_email'),
        );

        $this->load->view('admin/dashboard', $data);
    }

    public function utenti()
    {
        // Qui mostriamo la lista degli utenti, utile per controlli e report.
        $this->richiedi_admin();

        $data['utenti'] = $this->Utente_model->tutti();
        $this->load->view('admin/utenti', $data);
    }

    public function assegna_commesse($utente_id)
    {
        $this->richiedi_admin();

        $utente = $this->Utente_model->trova_per_id((int) $utente_id);
        if ( ! $utente)
        {
            show_404();
            return;
        }

        $data = array(
            'utente' => $utente,
            'commesse' => $this->Commessa_model->tutte(true),
            'assegnate_ids' => $this->Commessa_model->ids_commesse_assegnate_a_utente($utente_id),
        );

        $this->load->view('admin/assegna_commesse', $data);
    }

    public function salva_assegnazioni($utente_id)
    {
        $this->richiedi_admin();

        if ($this->input->method(TRUE) !== 'POST')
        {
            show_error('Metodo non consentito.', 405);
            return;
        }

        $utente = $this->Utente_model->trova_per_id((int) $utente_id);
        if ( ! $utente)
        {
            show_404();
            return;
        }

        $commesse_ids = $this->input->post('commesse');
        if ( ! is_array($commesse_ids))
        {
            $commesse_ids = array();
        }

        $this->Commessa_model->sincronizza_assegnazioni_utente($utente_id, $commesse_ids);

        redirect('admin/assegna-commesse/' . (int) $utente_id);
    }

    public function reset_password($utente_id)
    {
        $this->richiedi_admin();

        if ($this->input->method(TRUE) !== 'POST')
        {
            show_error('Metodo non consentito.', 405);
            return;
        }

        $utente = $this->Utente_model->trova_per_id((int) $utente_id);
        if ( ! $utente)
        {
            show_404();
            return;
        }

        $password_temporanea = $this->genera_password_temporanea();
        $hash = password_hash($password_temporanea, PASSWORD_DEFAULT);

        if ( ! $this->Utente_model->aggiorna_password((int) $utente_id, $hash))
        {
            $this->session->set_flashdata('notice_error', 'Impossibile reimpostare la password.');
        }
        else
        {
            $this->session->set_flashdata(
                'notice_success',
                'Password reimpostata per ' . trim($utente->nome . ' ' . $utente->cognome) . '. Password temporanea: ' . $password_temporanea
            );
        }

        $ruolo = $this->session->userdata('utente_ruolo');
        if ($ruolo === 'superadmin')
        {
            redirect('superadmin/utenti');
            return;
        }

        redirect('admin/utenti');
    }
}
